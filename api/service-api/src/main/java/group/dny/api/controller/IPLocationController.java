package group.dny.api.controller;

import group.dny.api.component.aop.ReqLogAnnotation;
import group.dny.api.component.constant.StatusEnum;
import group.dny.api.dto.ResultDTO;
import group.dny.api.entity.IPLocation;
import group.dny.api.service.IIPLocationService;
import group.dny.api.utils.ExceptionUtil;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.http.MediaType;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RequestMethod;
import org.springframework.web.bind.annotation.RequestParam;
import org.springframework.web.bind.annotation.RestController;

/**
 * @Description TODO
 * @ClassName IPLocationController
 * @Author lizhengfan
 * @Date 2019/5/30 18:33
 * @Version 1.0.0
 **/
@RestController
public class IPLocationController extends BaseController {
    @Autowired
    IIPLocationService iipLocationService;

    @ReqLogAnnotation
    @RequestMapping(value = "/v1/iplocation", method = RequestMethod.POST, produces = MediaType.APPLICATION_JSON_VALUE)
    public ResultDTO getIPLocation(@RequestParam("ip") String ip) {
        try {
            IPLocation ipLocation = iipLocationService.getIPLocationByIP(ip);
            return new ResultDTO(StatusEnum.SUCCESS, ipLocation);
        } catch (ExceptionUtil e) {
            return new ResultDTO(e.getValue(), e.getMap());
        }
    }
}
