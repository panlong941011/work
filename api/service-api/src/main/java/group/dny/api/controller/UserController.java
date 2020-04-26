package group.dny.api.controller;


import com.alibaba.fastjson.JSONObject;
import group.dny.api.component.aop.ReqLogAnnotation;
import group.dny.api.component.constant.StatusEnum;
import group.dny.api.configuration.BusinessConfiguration;
import group.dny.api.configuration.TokenFilter;
import group.dny.api.dto.ResultDTO;
import group.dny.api.service.IAccessTokenService;
import group.dny.api.service.IBuyerService;
import group.dny.api.utils.HttpClientUtil;
import group.dny.api.utils.NetworkUtil;
import org.apache.commons.lang.StringUtils;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.http.MediaType;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RequestParam;
import org.springframework.web.bind.annotation.RestController;

import java.util.HashMap;
import java.util.Map;


/**
 * <p>
 * 前端控制器
 * </p>
 *
 * @author lizhengfan
 * @since 2019-03-21
 */
@RestController
@RequestMapping(value = "/v1/user")
public class UserController extends BaseController {
    private static Logger logger = LoggerFactory.getLogger(TokenFilter.class);
    @Autowired
    IBuyerService buyerService;

    @Autowired
    IAccessTokenService accessTokenService;

    @Autowired
    BusinessConfiguration businessConfiguration;

    /**
     * 获取token
     *
     * @return
     */
    @ReqLogAnnotation
    @RequestMapping(value = "/token", produces = MediaType.APPLICATION_JSON_VALUE)
    public ResultDTO token(@RequestParam("sAppID") String sAppID, @RequestParam("sAppSec") String sAppSec, @RequestParam(required = false) String reqToken) {
        String baseUrl = businessConfiguration.getCasurl();

        String ipAddress = null;
        try {
            ipAddress = NetworkUtil.getIpAddress(this.request);
        } catch (Exception e) {
        }

        String status = null;
        JSONObject jsonObject = null;
        JSONObject dataObject = null;
        String token = null;


        //token为空，应该登录,否则应该登录
        if (StringUtils.isBlank(reqToken)) {
            String loginUrl = "/user/login";
            String loginStr = HttpClientUtil.doPost(baseUrl + loginUrl + "?sAppID=" + sAppID + "&sAppSec=" + sAppSec);
            jsonObject = JSONObject.parseObject(loginStr);

            dataObject = (JSONObject) jsonObject.get("data");
            token = (String) dataObject.get("access_token");
        } else {
            String validateUrl = "/user/validate";
            String validateStr = HttpClientUtil.doPost(baseUrl + validateUrl + "?token=" + reqToken + "&ip=" + ipAddress);
            jsonObject = JSONObject.parseObject(validateStr);
            token = (String) dataObject.get("access_token");
        }

        status = (String) jsonObject.get("status");
        Integer statusValue = Integer.parseInt(status);
        if (statusValue == 10001) {
            return new ResultDTO(StatusEnum.TOKEN_IP_NOT_VALIDATE);
        } else if (statusValue == 10003) {
            return new ResultDTO(StatusEnum.USERLOGIN_ERROR);
        } else if (statusValue == 10004) {
            return new ResultDTO(StatusEnum.USERLOGIN_ERROR);
        } else if (statusValue == 10007) {
            return new ResultDTO(StatusEnum.TOKEN_IS_EXPIRED);
        } else if (statusValue == 10000) {


            Map<String, Object> map = new HashMap<>();
            map.put("access_token", token);
            map.put("expires_in", 7200);
            return new ResultDTO(StatusEnum.SUCCESS, map);
        } else {
            return new ResultDTO(StatusEnum.FAILURE);
        }
    }
}
