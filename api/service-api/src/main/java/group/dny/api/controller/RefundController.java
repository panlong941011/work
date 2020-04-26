package group.dny.api.controller;


import group.dny.api.component.aop.ReqLogAnnotation;
import group.dny.api.component.constant.StatusEnum;
import group.dny.api.dto.ResultDTO;
import group.dny.api.entity.Order;
import group.dny.api.entity.OrderDetail;
import group.dny.api.entity.Refund;
import group.dny.api.entity.RefundReturn;
import group.dny.api.service.IOrderDetailService;
import group.dny.api.service.IOrderService;
import group.dny.api.service.IRefundReturnService;
import group.dny.api.service.IRefundService;
import group.dny.api.utils.ExceptionUtil;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.http.MediaType;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RequestParam;
import org.springframework.web.bind.annotation.RestController;

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
@RequestMapping(value = "/v1/refund")
public class RefundController extends BaseController {
    @Autowired
    IRefundService refundService;

    @Autowired
    IRefundReturnService refundReturnService;

    @Autowired
    IOrderService orderService;

    @Autowired
    IOrderDetailService orderDetailService;

    /**
     * 退款申请
     *
     * @return
     */
    @ReqLogAnnotation
    @RequestMapping(value = "/apply", produces = MediaType.APPLICATION_JSON_VALUE)
    public ResultDTO apply(@RequestParam("sn") String sn,
                           @RequestParam("ordersn") String ordersn,
                           @RequestParam("pid") Integer pid,
                           @RequestParam("itemtotal") Integer itemtotal,
                           @RequestParam("refunditem") Integer refunditem,
                           @RequestParam("type") String type
    ) {
        String reason = null;
        if (this.getParamsList().contains("reason")) {
            reason = request.getParameter("reason");
        }

        String img = null;
        if (this.getParamsList().contains("img")) {
            img = request.getParameter("img");
        }

        try {
            refundService.refundApply(sn, ordersn, pid, itemtotal, refunditem, type, reason, img);
            return new ResultDTO(StatusEnum.SUCCESS);
        } catch (ExceptionUtil e) {
            return new ResultDTO(e.getValue());
        }
    }

    /**
     * 退款状态
     *
     * @return
     */
    @ReqLogAnnotation
    @RequestMapping(value = "/status", produces = MediaType.APPLICATION_JSON_VALUE)
    public ResultDTO status(@RequestParam("sn") String sn) {
        try {
            Map<String, Object> statusMap = refundService.refundStatus(sn);
            return new ResultDTO(StatusEnum.SUCCESS, statusMap);
        } catch (ExceptionUtil e) {
            return new ResultDTO(e.getValue());
        }
    }

    /**
     * 标记为退款状态
     *
     * @return
     */
    @ReqLogAnnotation
    @RequestMapping(value = "/setrefund", produces = MediaType.APPLICATION_JSON_VALUE)
    public ResultDTO setrefund(@RequestParam("sn") String sn,
                               @RequestParam("pID") Integer pID) {
        try {
            refundService.updateOrderRefund(sn, pID);
            return new ResultDTO(StatusEnum.SUCCESS);
        } catch (ExceptionUtil e) {
            return new ResultDTO(e.getValue());
        }
    }

    /**
     * 撤销退款
     *
     * @return
     */
    @ReqLogAnnotation
    @RequestMapping(value = "/cancel", produces = MediaType.APPLICATION_JSON_VALUE)
    public ResultDTO cancel(@RequestParam("sn") String sn,
                            @RequestParam("ordersn") String ordersn,
                            @RequestParam("pID") Integer pID) {
        try {
            StatusEnum statusEnum = refundService.refundCancel(sn, ordersn, pID);
            return new ResultDTO(statusEnum);
        } catch (ExceptionUtil e) {
            return new ResultDTO(e.getValue());
        }
    }

    /**
     * 提交退货物流信息
     *
     * @return
     */
    @ReqLogAnnotation
    @RequestMapping(value = "/returnship", produces = MediaType.APPLICATION_JSON_VALUE)
    public ResultDTO returnship(@RequestParam("sn") String sn,
                                @RequestParam("shipno") String shipno,
                                @RequestParam("shipcompany") String shipcompany,
                                @RequestParam("mobile") String mobile,
                                @RequestParam(required = false) String img
    ) {
        try {
            Refund refund = refundService.getRefundBySn(sn);
            Integer orderID = refund.getOrderID();
            Integer orderDetailID = refund.getOrderDetailID();

            Order order = orderService.getOrderByID(orderID);

            String orderStatus = order.getStatusID();
            if (orderStatus.equals(StatusEnum.ORDER_SUCCESS.getValue())) {
                return new ResultDTO(StatusEnum.ORDER_STATUS_NOT_ALLOW);
            }

            OrderDetail orderDetail = orderDetailService.getOrderDetailByID(orderDetailID);

            String detailStatus = orderDetail.getStatusID();
            if (detailStatus != null && !detailStatus.equals("refunding")) {
                return new ResultDTO(StatusEnum.REFUND_DETAIL_NOT_EXIST);
            }

            refund.setStatusID("waitconfirmapply");
            refundService.updateRefundStatus(refund);

            RefundReturn refundReturn = new RefundReturn();
            refundReturn.setSName(sn);
            refundReturn.setSShipVoucher(img);
            refundReturn.setShipCompanyID(shipcompany);
            refundReturn.setSMobile(mobile);
            refundReturn.setSShipNo(shipno);
            refundReturn.setStatusID("delivered");

            try {
                refundReturnService.updateShipInfo(refundReturn);
            } catch (RuntimeException e) {
                System.out.println(e);
            }


            return new ResultDTO(StatusEnum.SUCCESS);
        } catch (ExceptionUtil e) {
            return new ResultDTO(e.getValue());
        }
    }

    /**
     * 修改退货物流信息
     *
     * @return
     */
    @ReqLogAnnotation
    @RequestMapping(value = "/modifyreturnship", produces = MediaType.APPLICATION_JSON_VALUE)
    public ResultDTO modifyreturnship(@RequestParam("sn") String sn,
                                      @RequestParam("shipno") String shipno,
                                      @RequestParam("shipcompany") String shipcompany,
                                      @RequestParam("mobile") String mobile,
                                      @RequestParam(required = false) String img
    ) {
        try {
            Refund refund = refundService.getRefundBySn(sn);
            Integer orderID = refund.getOrderID();
            Integer orderDetailID = refund.getOrderDetailID();

            Order order = orderService.getOrderByID(orderID);
            String orderStatus = order.getStatusID();
            if (orderStatus.equals(StatusEnum.ORDER_SUCCESS.getValue())) {
                return new ResultDTO(StatusEnum.ORDER_STATUS_NOT_ALLOW);
            }

            OrderDetail orderDetail = orderDetailService.getOrderDetailByID(orderDetailID);

            String detailStatus = orderDetail.getStatusID();
            if (detailStatus != null && !detailStatus.equals("refunding")) {
                return new ResultDTO(StatusEnum.REFUND_DETAIL_NOT_EXIST);
            }

            refund.setStatusID("waitconfirmapply");
            refundService.updateRefundStatus(refund);

            RefundReturn refundReturn = new RefundReturn();
            refundReturn.setSName(sn);
            refundReturn.setSShipVoucher(img);
            refundReturn.setShipCompanyID(shipcompany);
            refundReturn.setSMobile(mobile);
            refundReturn.setSShipNo(shipno);
            refundReturn.setStatusID("delivered");


            refundReturnService.updateShipInfo(refundReturn);
            return new ResultDTO(StatusEnum.SUCCESS);
        } catch (ExceptionUtil e) {
            return new ResultDTO(e.getValue());
        }
    }
}
