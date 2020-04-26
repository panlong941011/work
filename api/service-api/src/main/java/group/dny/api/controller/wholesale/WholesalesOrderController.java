package group.dny.api.controller.wholesale;


import group.dny.api.component.aop.ReqLogAnnotation;
import group.dny.api.component.constant.StatusEnum;
import group.dny.api.component.model.OrderModel;
import group.dny.api.controller.BaseController;
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

import java.util.Map;


/**
 * @Description 渠道订单
 * @ClassName WholesalesOrderController
 * @Author lizhengfan
 * @Date 2019/5/15 9:15
 * @Version 1.0.0
 **/
@RestController
@RequestMapping(value = "/wholesale/order")
public class WholesalesOrderController extends BaseController {
    @Autowired
    private IOrderService orderService;

    @Autowired
    private IPreOrderService preOrderService;

    /**
     * @Description 渠道下单
     * @Author lizhengfan
     * @Date 2019/5/15 11:18
     * @Param orderModel
     * @Return ResultDTO
     **/
    @ReqLogAnnotation
    @RequestMapping(value = "/submit", produces = MediaType.APPLICATION_JSON_VALUE)
    public ResultDTO submit(OrderModel orderModel) {
        try {
            Map<String, Object> map = orderService.submit(orderModel);
            return new ResultDTO(StatusEnum.SUCCESS, map);
        } catch (ExceptionUtil e) {
            return new ResultDTO(e.getValue());
        }
    }

    /**
     * @Description 渠道确认下单
     * @Author lizhengfan
     * @Date 2019/5/15 11:16
     * @Param sn 订单号
     * @Return ResultDTO
     **/
    @ReqLogAnnotation
    @RequestMapping(value = "/confirmPay", produces = MediaType.APPLICATION_JSON_VALUE)
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
     * @Description 供应商订单运费
     * @Author lizhengfan
     * @Date 2019/5/15 11:17
     * @Param sku
     * @Param cityName
     * @Param provinceName
     * @Return ResultDTO
     **/
    @ReqLogAnnotation
    @RequestMapping(value = "/ship", produces = MediaType.APPLICATION_JSON_VALUE, method = RequestMethod.POST)
    public ResultDTO ship(@RequestParam("sku") String sku, @RequestParam("cityName") String cityName, @RequestParam("provinceName") String provinceName) {
        try {
            Map<String, Object> map = orderService.ship(sku, cityName, provinceName, true);
            return new ResultDTO(StatusEnum.SUCCESS, map);
        } catch (ExceptionUtil e) {
            return new ResultDTO(e.getValue(), e.getMap());
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
}
