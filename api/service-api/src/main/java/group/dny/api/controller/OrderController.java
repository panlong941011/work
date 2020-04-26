package group.dny.api.controller;


import group.dny.api.component.aop.ReqLogAnnotation;
import group.dny.api.component.constant.StatusEnum;
import group.dny.api.component.model.OrderModel;
import group.dny.api.component.model.OrderStatusModel;
import group.dny.api.dto.ResultDTO;
import group.dny.api.service.IOrderService;
import group.dny.api.service.IPreOrderService;
import group.dny.api.utils.ExceptionUtil;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.http.MediaType;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RequestMethod;
import org.springframework.web.bind.annotation.RequestParam;
import org.springframework.web.bind.annotation.RestController;

import java.util.List;
import java.util.Map;

/**
 * @Description 订单
 * @ClassName OrderController
 * @Author lizhengfan
 * @Date 2019/5/15 9:15
 * @Version 1.0.0
 **/
@RestController
@RequestMapping(value = "/v1/order", produces = MediaType.APPLICATION_JSON_VALUE)
public class OrderController extends BaseController {

    @Autowired
    private IOrderService orderService;

    @Autowired
    private IPreOrderService preOrderService;

    /**
     * @Description 提交订单
     * @Author lizhengfan
     * @Date 2019/5/15 9:12
     * @Param orderModel
     * @Return ResultDTO
     **/
    @ReqLogAnnotation
    @RequestMapping(value = "/submit", method = RequestMethod.POST)
    public ResultDTO submit(OrderModel orderModel) {
        try {
            Map<String, Object> map = orderService.submit(orderModel);
            return new ResultDTO(StatusEnum.SUCCESS, map);
        } catch (ExceptionUtil e) {
            return new ResultDTO(e.getValue(), e.getMap());
        }
    }

    /**
     * @Description 订单运费
     * @Author lizhengfan
     * @Date 2019/5/15 9:12
     * @Param sku  订单sku信息
     * @Param cityName 城市名
     * @Param provinceName 省份名
     * @Return ResultDTO
     **/
    @ReqLogAnnotation
    @RequestMapping(value = "/ship", method = RequestMethod.POST)
    public ResultDTO ship(@RequestParam("sku") String sku, @RequestParam("cityName") String cityName, @RequestParam("provinceName") String provinceName) {
        try {
            Map<String, Object> map = orderService.ship(sku, cityName, provinceName, false);
            return new ResultDTO(StatusEnum.SUCCESS, map);
        } catch (ExceptionUtil e) {
            return new ResultDTO(e.getValue(), e.getMap());
        }
    }

    /**
     * @Description 确认下单
     * @Author lizhengfan
     * @Date 2019/5/15 9:36
     * @Param sn 订单号
     * @Return ResultDTO
     **/
    @ReqLogAnnotation
    @RequestMapping(value = "/confirmPay", method = RequestMethod.POST)
    public ResultDTO confirmPay(@RequestParam("sn") String sn) {
        try {
            orderService.confirmPay(sn);
            return new ResultDTO(StatusEnum.SUCCESS);
        } catch (ExceptionUtil e) {
            String message = e.getMessage();
            if (message != null && message.length() > 0) {
                return new ResultDTO(Integer.parseInt(e.getValue().getKey()), message, null);
            }

            return new ResultDTO(e.getValue());
        }
    }

    /**
     * @Description 订单状态
     * @Author lizhengfan
     * @Date 2019/5/15 9:36
     * @Param sn 订单号
     * @Return ResultDTO
     **/
    @ReqLogAnnotation
    @RequestMapping(value = "/status", method = RequestMethod.POST)
    public ResultDTO status(@RequestParam("sn") String sn) {
        try {
            List<OrderStatusModel> resultList = orderService.status(sn);
            return new ResultDTO(StatusEnum.SUCCESS, resultList);
        } catch (ExceptionUtil e) {
            return new ResultDTO(e.getValue(), e.getMap());
        }
    }

    /**
     * @Description 收货确认
     * @Author lizhengfan
     * @Date 2019/5/15 10:50
     * @Param sn 订单号
     * @Return ResultDTO
     **/
    @ReqLogAnnotation
    @RequestMapping(value = "/confirmReceived", method = RequestMethod.POST)
    public ResultDTO confirmReceived(@RequestParam("sn") String sn) {
        try {
            orderService.confirmReceived(sn);
            return new ResultDTO(StatusEnum.SUCCESS);
        } catch (ExceptionUtil e) {
            return new ResultDTO(e.getValue());
        }
    }

    /**
     * @Description 修改收货地址
     * @Author lizhengfan
     * @Date 2019/5/15 10:51
     * @Param orderModel
     * @Return ResultDTO
     **/
    @ReqLogAnnotation
    @RequestMapping(value = "/modifyAddress", method = RequestMethod.POST)
    public ResultDTO modifyAddress(OrderModel orderModel) {
        try {
            orderService.modifyAddress(orderModel);
            return new ResultDTO(StatusEnum.SUCCESS);
        } catch (ExceptionUtil e) {
            return new ResultDTO(e.getValue());
        }
    }

    /**
     * @Description 客户取消订单
     * @Author lizhengfan
     * @Date 2019/5/15 10:54
     * @Param sn 订单号
     * @Return ResultDTO
     **/
    @ReqLogAnnotation
    @RequestMapping(value = "/cancel", method = RequestMethod.POST)
    public ResultDTO cancel(@RequestParam("sn") String sn) {
        try {
            preOrderService.updatePreOrderStatus(sn, true);
            return new ResultDTO(StatusEnum.SUCCESS);
        } catch (ExceptionUtil e) {
            return new ResultDTO(e.getValue());
        }
    }

    @ReqLogAnnotation
    @RequestMapping(value = "/fix", method = RequestMethod.POST)
    public ResultDTO fix() {
        try {
            orderService.fix();
            return new ResultDTO(StatusEnum.SUCCESS);
        } catch (ExceptionUtil e) {
            return new ResultDTO(e.getValue());
        }
    }
}
