package group.dny.api.configuration;

import com.alibaba.druid.util.PatternMatcher;
import com.alibaba.druid.util.ServletPathMatcher;
import com.alibaba.fastjson.JSONObject;
import group.dny.api.component.common.Common;
import group.dny.api.component.constant.StatusEnum;
import group.dny.api.dto.ResultDTO;
import group.dny.api.service.IAccessTokenService;
import group.dny.api.service.IBuyerService;
import group.dny.api.utils.DateUtils;
import group.dny.api.utils.HttpClientUtil;
import group.dny.api.utils.NetworkUtil;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Component;

import javax.servlet.*;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.servlet.http.HttpServletResponseWrapper;
import java.io.IOException;
import java.io.PrintWriter;
import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.*;

@Component
public class TokenFilter implements Filter {
    private static Logger logger = LoggerFactory.getLogger(TokenFilter.class);
    public static final String PARAM_NAME_EXCLUSIONS = "exclusions";
    private Set<String> excludesPattern;
    protected String contextPath;
    protected PatternMatcher pathMatcher = new ServletPathMatcher();

    @Autowired
    private IAccessTokenService accessTokenService;

    @Autowired
    IBuyerService buyerService;

    @Autowired
    WholesaleConfig wholesaleConfig;

    @Autowired
    BusinessConfiguration businessConfiguration;

    public String getContextPath() {
        return contextPath;
    }

    public void setContextPath(String contextPath) {
        this.contextPath = contextPath;
    }

    @Override
    public void init(FilterConfig filterConfig) throws ServletException {
        String param = filterConfig.getInitParameter(PARAM_NAME_EXCLUSIONS);
        if (param != null && param.trim().length() != 0) {
            this.excludesPattern = new HashSet(Arrays.asList(param.split("\\s*,\\s*")));
        }

    }

    @Override
    public void doFilter(ServletRequest servletRequest, ServletResponse servletResponse, FilterChain filterChain) throws IOException, ServletException {
        HttpServletRequest httpRequest = (HttpServletRequest) servletRequest;
        HttpServletResponse httpResponse = (HttpServletResponse) servletResponse;
        TokenFilter.StatHttpServletResponseWrapper responseWrapper = new TokenFilter.StatHttpServletResponseWrapper(httpResponse);
        String requestURI = this.getRequestURI(httpRequest);

        if (excludesPattern == null) {
            excludesPattern = new HashSet<>();
            excludesPattern.add("/v1/user/token");
            excludesPattern.add("/cat/s/router");
        }

        if (this.isExclusion(requestURI)) {
            filterChain.doFilter(servletRequest, servletResponse);
        } else {
            ResultDTO result = new ResultDTO();
            String ipAddress = null;
            try {
                ipAddress = NetworkUtil.getIpAddress(httpRequest);
            } catch (Exception e) {
            }

            //渠道商品路由控制
            if (requestURI.indexOf("wholesale") > -1) {
                if (!wholesaleConfig.getAllowIP().contains(ipAddress)) {
                    result.setResultMessage(StatusEnum.TOKEN_IP_NOT_VALIDATE);
                }
            } else {
                //通过参数获取token
                String token = servletRequest.getParameter("token");
                logger.info("访问Uri:" + requestURI + "token:" + token);


                //判断token是否为空
                if (token == null || token.length() == 0) {
                    result.setResultMessage(StatusEnum.TOKEN_IS_NOT_EMPTY);
                }

                //判断是否过期
                Date userExpiredDate = Common.userTokenMap.get(token);
                Date nowTime = new Date();

                int mapCount = Common.userTokenMap.size();
                if (mapCount > 100) {
                    Common.userTokenMap.clear();
                }

                if (userExpiredDate == null || DateUtils.dateCompare(userExpiredDate, nowTime)) {
                    String baseUrl = businessConfiguration.getCasurl();
                    String validateUrl = "/user/validate";

                    String validateStr = HttpClientUtil.doPost(baseUrl + validateUrl + "?token=" + token + "&ip=" + ipAddress);
                    JSONObject validateObject = JSONObject.parseObject(validateStr);
                    if (validateObject != null) {
                        if (validateObject.get("status").equals("10001")) {
                            result.setResultMessage(StatusEnum.TOKEN_IP_NOT_VALIDATE);
                        } else if (validateObject.get("status").equals("10007")) {
                            result.setResultMessage(StatusEnum.TOKEN_IS_EXPIRED);
                        } else if (validateObject.get("status").equals("10005")) {
                            result.setResultMessage(StatusEnum.TOKEN_IS_NOT_EMPTY);
                        } else if (validateObject.get("status").equals("10000")) {
                            JSONObject data = (JSONObject) validateObject.get("data");
                            String dExpireDateStr = (String) data.get("dExpireDate");
                            dExpireDateStr = dExpireDateStr.substring(0, 19);
                            dExpireDateStr = dExpireDateStr.replace("T", " ");
                            SimpleDateFormat formatter = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
                            Date validateTime = null;
                            try {
                                validateTime = formatter.parse(dExpireDateStr);
                            } catch (ParseException e) {
                            }

                            Common.userTokenMap.put(token, validateTime);
                        }
                    }
                }
            }

            if (result.getStatus() != null && (result.getStatus() == Integer.parseInt(StatusEnum.TOKEN_IP_NOT_VALIDATE.getKey()) || result.getStatus() == Integer.parseInt(StatusEnum.TOKEN_IS_NOT_EMPTY.getKey()) || result.getStatus() == Integer.parseInt(StatusEnum.TOKEN_IS_EXPIRED.getKey()))) {
                //输出结果到客户端
                servletResponse.setCharacterEncoding("utf-8");
                servletResponse.setContentType("application/json; charset=utf-8");
                PrintWriter out = servletResponse.getWriter();

                out.write(JSONObject.toJSONString(result));
                out.flush();
                out.close();
            } else {
                filterChain.doFilter(servletRequest, servletResponse);
            }

        }
    }

    @Override
    public void destroy() {

    }

    public boolean isExclusion(String requestURI) {
        if (this.excludesPattern == null) {
            return false;
        } else {
            if (requestURI.indexOf("swagger") > -1) {
                return true;
            }

            if (requestURI.indexOf("api-docs") > -1) {
                return true;
            }

            if (this.contextPath != null && requestURI.startsWith(this.contextPath)) {
                requestURI = requestURI.substring(this.contextPath.length());
                if (!requestURI.startsWith("/")) {
                    requestURI = "/" + requestURI;
                }
            }

            Iterator i$ = this.excludesPattern.iterator();

            String pattern;
            do {
                if (!i$.hasNext()) {
                    return false;
                }

                pattern = (String) i$.next();
            } while (!this.pathMatcher.matches(pattern, requestURI));

            return true;
        }
    }

    public static final class StatHttpServletResponseWrapper extends HttpServletResponseWrapper implements HttpServletResponse {
        private int status = 200;

        public StatHttpServletResponseWrapper(HttpServletResponse response) {
            super(response);
        }

        @Override
        public void setStatus(int statusCode) {
            super.setStatus(statusCode);
            this.status = statusCode;
        }

        public void setStatus(int statusCode, String statusMessage) {
            super.setStatus(statusCode, statusMessage);
            this.status = statusCode;
        }

        @Override
        public void sendError(int statusCode, String statusMessage) throws IOException {
            super.sendError(statusCode, statusMessage);
            this.status = statusCode;
        }

        @Override
        public void sendError(int statusCode) throws IOException {
            super.sendError(statusCode);
            this.status = statusCode;
        }

        @Override
        public int getStatus() {
            return this.status;
        }
    }

    public String getRequestURI(HttpServletRequest request) {
        return request.getRequestURI();
    }
}
