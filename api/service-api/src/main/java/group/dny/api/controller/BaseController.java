package group.dny.api.controller;

import group.dny.api.dto.ResultDTO;
import org.springframework.beans.factory.annotation.Autowired;

import javax.servlet.http.HttpServletRequest;
import java.util.ArrayList;
import java.util.Enumeration;
import java.util.List;

public class BaseController {

    protected Integer currentPage = 1;
    protected Integer pageSize = 20;

    private static final String CURRENTPAGE = "currentPage";
    private static final String PAGESIZE = "pageSize";

    public BaseController() {

    }

    public List<String> getParamsList() {
        this.parse();
        return paramsList;
    }

    private List<String> paramsList = null;

    @Autowired
    protected HttpServletRequest request;

    protected ResultDTO result = null;

    /**
     * 检查是否存在分页参数
     */
    public void checkPager() {
        if (this.paramsList.contains(CURRENTPAGE)) {
            currentPage = Integer.parseInt(request.getParameter(CURRENTPAGE));
        }

        if (this.paramsList.contains(PAGESIZE)) {
            pageSize = Integer.parseInt(request.getParameter(PAGESIZE));
        }
    }

    /**
     * 获取所有的请求参数
     */
    public void parse() {
        paramsList = new ArrayList();
        Enumeration paraEnum = request.getParameterNames();
        while (paraEnum.hasMoreElements()) {
            String paramName = (String) paraEnum.nextElement();
            paramsList.add(paramName);
        }
    }
}
