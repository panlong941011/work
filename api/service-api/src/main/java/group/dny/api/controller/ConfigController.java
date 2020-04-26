package group.dny.api.controller;


import group.dny.api.component.aop.ReqLogAnnotation;
import group.dny.api.component.constant.StatusEnum;
import group.dny.api.dto.ResultDTO;
import group.dny.api.entity.AccessToken;
import group.dny.api.entity.Buyer;
import group.dny.api.service.IAccessTokenService;
import group.dny.api.service.IBuyerService;
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
public class ConfigController extends BaseController {
    @Autowired
    IAccessTokenService accessTokenService;

    @Autowired
    IBuyerService buyerService;

    /**
     * 获取token
     *
     * @return
     */
    @ReqLogAnnotation
    @RequestMapping(value = "/v1/config", produces = MediaType.APPLICATION_JSON_VALUE)
    public ResultDTO index(@RequestParam(required = false, defaultValue = "") String token) {
        //判断是否有token
        if (token != null && token.length() > 0) {
            AccessToken accessToken = accessTokenService.getAccessTokenByToken(token);
            Integer buyerID = accessToken.getBuyerID();
            Buyer buyer = buyerService.findBuyerByID(buyerID);
            String sConfig = buyer.getSConfig();
            Map<String, Object> map = new HashMap<>();
            map.put("sConfig", sConfig);

            return new ResultDTO(StatusEnum.SUCCESS, map);
        } else {
            return new ResultDTO(StatusEnum.TOKEN_IS_NOT_EMPTY);
        }
    }
}
