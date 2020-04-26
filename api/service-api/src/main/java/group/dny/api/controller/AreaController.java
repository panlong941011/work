package group.dny.api.controller;


import group.dny.api.component.aop.ReqLogAnnotation;
import group.dny.api.component.constant.StatusEnum;
import group.dny.api.dto.ResultDTO;
import group.dny.api.entity.Area;
import group.dny.api.service.IAreaService;
import group.dny.api.utils.ExceptionUtil;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.http.MediaType;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RequestMethod;
import org.springframework.web.bind.annotation.RequestParam;
import org.springframework.web.bind.annotation.RestController;

import java.util.List;


/**
 * @Description 地址
 * @ClassName AreaController
 * @Author lizhengfan
 * @Date 2019/5/15 9:15
 * @Version 1.0.0
 **/
@RestController
@RequestMapping(value = "/v1/area", produces = MediaType.APPLICATION_JSON_VALUE)
public class AreaController extends BaseController {
    @Autowired
    IAreaService areaService;

    /**
     * @Description 获取省市区
     * @Author lizhengfan
     * @Date 2019/5/15 15:09
     * @Param UpID 上级ID
     * @Return ResultDTO
     **/
    @ReqLogAnnotation
    @RequestMapping(value = "/list", method = RequestMethod.POST)
    public ResultDTO list(@RequestParam(required = false) String UpID) {
        List<Area> list = null;
        try {
            Area area = new Area();
            area.setUpID(UpID);
            list = areaService.getAreaList(area);
            return new ResultDTO(StatusEnum.SUCCESS, list);
        } catch (ExceptionUtil e) {
            return new ResultDTO(e.getValue());
        }
    }

    /**
     * @Description 获取城市信息
     * @Author lizhengfan
     * @Date 2019/5/16 9:38
     * @Param province
     * @Param city
     * @Return ResultDTO
     **/
    @ReqLogAnnotation
    @RequestMapping(value = "/city", method = RequestMethod.POST)
    public ResultDTO city(@RequestParam(required = false) String province, @RequestParam(required = false) String city) {
        Area area = null;
        try {
            Area provinceArea = areaService.getAreaByProvince(province);
            Area cityParam = new Area();
            cityParam.setUpID(provinceArea.getId());
            cityParam.setSName(city);
            area = areaService.getAreaByCity(cityParam);
            return new ResultDTO(StatusEnum.SUCCESS, area);
        } catch (ExceptionUtil e) {
            return new ResultDTO(e.getValue());
        }
    }
}
