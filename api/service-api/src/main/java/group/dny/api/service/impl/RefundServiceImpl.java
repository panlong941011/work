package group.dny.api.service.impl;

import com.alibaba.fastjson.JSON;
import com.baomidou.mybatisplus.extension.service.impl.ServiceImpl;
import group.dny.api.component.constant.StatusEnum;
import group.dny.api.entity.*;
import group.dny.api.mapper.RefundMapper;
import group.dny.api.service.*;
import group.dny.api.utils.ArithUtil;
import group.dny.api.utils.DateUtils;
import group.dny.api.utils.ExceptionUtil;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;
import org.springframework.transaction.annotation.Transactional;

import java.math.BigDecimal;
import java.util.Date;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

/**
 * <p>
 * 服务实现类
 * </p>
 *
 * @author lizhengfan
 * @since 2019-04-24
 */
@Service
public class RefundServiceImpl extends ServiceImpl<RefundMapper, Refund> implements IRefundService {
    @Autowired
    RefundMapper refundMapper;

    @Autowired
    IOrderService orderService;

    @Autowired
    IProductService productService;

    @Autowired
    ISupplierService supplierService;

    @Autowired
    IOrderDetailService orderDetailService;

    @Autowired
    IRefundLogService refundLogService;

    @Autowired
    IMallConfigService mallConfigService;

    @Autowired
    IRefundReturnService refundReturnService;

    @Autowired
    IOrderAddressService orderAddressService;

    @Autowired
    IAreaService areaService;

    @Transactional
    @Override
    public void refundApply(String sn, String ordersn, Integer pid, Integer itemtotal, Integer refunditem, String type, String reason, String img) throws RuntimeException {
        Refund haveRefund = this.getRefundBySn(sn);
        if (haveRefund != null) {
            throw new ExceptionUtil(StatusEnum.REFUND_RESUBMIT);
        }

//        List<Order> orderList = orderService.getOrderBySn(ordersn);
//        if (orderList == null || orderList.size() == 0) {
//            throw new ExceptionUtil(StatusEnum.ORDER_NOT_EXIST);
//        }

        Product product = productService.getProductById(pid);
        if (product == null) {
            throw new ExceptionUtil(StatusEnum.PRODUCT_NOT_EXIST);
        }

        Integer supplierID = product.getSupplierID();
        Supplier supplier = supplierService.getSupplierByID(supplierID);

        if (supplier == null) {
            throw new ExceptionUtil(StatusEnum.SUPPLIER_NOT_EXIST);
        }

        String sAddress = supplier.getSRefundAddress();

        String refundStatusID = "waitconfirmapply";

        if (!type.equals("onlymoney")) {
            refundStatusID = "return";
        }

        Map<String, Object> detailMap = new HashMap<>();
        detailMap.put("sClientSN", ordersn);
        detailMap.put("ProductID", pid);

        OrderDetail orderDetail = orderDetailService.getDetailByClientSnAndPID(detailMap);


        if (orderDetail == null) {
            throw new ExceptionUtil(StatusEnum.ORDER_NOT_EXIST);
        }

        Integer orderID = orderDetail.getOrderID();

        Order order = orderService.getOrderByID(orderID);
        if (order == null) {
            throw new ExceptionUtil(StatusEnum.ORDER_NOT_EXIST);
        }

        String orderStatus = order.getStatusID();
        Integer buyerID = order.getBuyerID();

        String detailStatus = orderDetail.getStatusID();

//        if (detailStatus != null && detailStatus.equals("refunding")) {
//            throw new ExceptionUtil(StatusEnum.REFUND_ORDER_DETAIL_INREFUND);
//        }

        if (!type.equals("onlymoney") && orderDetail.getDShipDate() != null && !orderDetail.getBShiped()) {
            throw new ExceptionUtil(StatusEnum.REFUND_TYPE_ERROR);
        }

        Integer OrderDetailID = orderDetail.getLID();
        BigDecimal fBuyerPaidTotal = orderDetail.getFBuyerPaidTotal();
        BigDecimal fSupplierIncomeTotal = orderDetail.getFSupplierIncomeTotal();
        Integer shipTemplateID = orderDetail.getShipTemplateID();
        BigDecimal fBuyerRefund = BigDecimal.valueOf(0);
        BigDecimal fBuyerRefundProduct = BigDecimal.valueOf(0);
        BigDecimal fSupplierRefund = BigDecimal.valueOf(0);
        BigDecimal fSupplierRefundProduct = BigDecimal.valueOf(0);
        orderDetail.setOrderID(orderID);

        String sShipNo = orderDetail.getSShipNo();

        OrderDetail detailParam = new OrderDetail();
        detailParam.setOrderID(orderID);
        detailParam.setProductID(pid);
        if (sShipNo == null) {
            detailParam.setShipTemplateID(shipTemplateID);
            BigDecimal fShip = orderDetail.getFShip();
            if (fShip == null) fShip = BigDecimal.valueOf(0);
            if (fBuyerPaidTotal == null) fBuyerPaidTotal = BigDecimal.valueOf(0);

            BigDecimal fTotalPrice = orderDetailService.countProductPrice(detailParam);
            BigDecimal fTotalShip = ArithUtil.mulRounding(fShip, ArithUtil.divRounding(fBuyerPaidTotal, fTotalPrice, 2), 2);

            fBuyerRefund = ArithUtil.addRounding(fBuyerPaidTotal, fTotalShip, 2);
            fBuyerRefundProduct = fBuyerPaidTotal;
            fSupplierRefund = ArithUtil.addRounding(fSupplierIncomeTotal, fTotalShip, 2);
            fSupplierRefundProduct = fSupplierIncomeTotal;
        }

        Refund refund = new Refund();

        if (fBuyerRefund != null && fBuyerRefund.floatValue() > 0) {
            refund.setFBuyerRefund(fBuyerRefund);
        }

        if (fSupplierRefund != null && fSupplierRefund.floatValue() > 0) {
            refund.setFSupplierRefund(fSupplierRefund);
        }

        Date nowtime = new Date();
        refund.setSName(sn);
        refund.setDNewDate(nowtime);
        refund.setDEditDate(nowtime);
        refund.setBuyerID(buyerID);
        refund.setSupplierID(supplierID);
        refund.setStatusID(refundStatusID);
        refund.setTypeID(type);
        refund.setOrderID(orderID);
        refund.setOrderDetailID(OrderDetailID);
        refund.setFBuyerPaidTotal(fBuyerPaidTotal);
        refund.setFSupplierIncomeTotal(fSupplierIncomeTotal);
        refund.setSReason(reason);
        refund.setSAddress(sAddress);
        refund.setShipTemplateID(shipTemplateID);
        refund.setLRefundItem(refunditem);
        refund.setLItemTotal(itemtotal);
//        refund.setFRefundProduct(fBuyerRefundProduct);
        refund.setFBuyerRefundProduct(fBuyerRefundProduct);
        refund.setFSupplierRefundProduct(fSupplierRefundProduct);
        refund.setSRefundVoucher(img);
        refund.setFProductPrice(fBuyerPaidTotal);

        BigDecimal fRefundProduct = ArithUtil.mulRounding(ArithUtil.divRounding(refunditem, itemtotal, 2), fBuyerPaidTotal, 2);
        refund.setFRefundProduct(fRefundProduct);

        refundMapper.saveRefund(refund);

        Integer refundID = refund.getLID();
        RefundLog refundLog = new RefundLog();
        refundLog.setRefundID(refundID);
        refundLog.setSWhoDo("买家");
        refundLog.setStatusID("apply");

        String typeStr = null;
        if (type.equals("onlymoney")) {
            typeStr = "仅退款";
        } else {
            typeStr = "退款退货";
        }

        String imgStr = img;
        if (img == null || img.length() == 0) {
            imgStr = "--";
        }

        //如果是退款退货
        if (!type.equals("onlymoney")) {
            RefundReturn refundReturn = new RefundReturn();
            refundReturn.setRefundID(refundID);
            refundReturn.setDNewDate(nowtime);
            refundReturn.setSShipVoucher(imgStr);
            refundReturn.setSName(sn);
            refundReturn.setSupplierID(supplierID);
            refundReturn.setStatusID("waitreturn");

            try {
                refundReturnService.insertRefundReturn(refundReturn);
            } catch (Exception e) {
                throw new ExceptionUtil(StatusEnum.SYS_ERROR);
            }
        }

        Map<String, Object> mMap = new HashMap<>();
        mMap.put("退款类型", typeStr);
        mMap.put("退款原因", reason);
        mMap.put("退款说明", "");
        mMap.put("退款凭证", imgStr);

        String msgStr = JSON.toJSONString(mMap);
        refundLog.setSMessage(msgStr);
        refundLog.setDNewDate(new Date());
        refundLogService.insertRefundLog(refundLog);

        orderDetail.setRefundID(refundID);
        orderDetail.setStatusID("refunding");

        orderDetailService.updateRefundStatus(orderDetail);

        order.setRefundStatusID("refunding");
        orderService.updateRefundStatus(order);
    }


    @Override
    public Map<String, Object> refundStatus(String sn) {
        Refund refund = this.getRefundBySn(sn);
        if (sn == null) {
            throw new ExceptionUtil(StatusEnum.REFUND_AMOUNT_ERROR);
        }

        String status = refund.getStatusID();

        Map<String, Object> resultMap = new HashMap<>();

        if (status.equals("success")) {
            resultMap.put("status", "success");
            resultMap.put("tip", "退款成功");
            resultMap.put("buyerrefund", refund.getFBuyerRefund());
            resultMap.put("supplierrefund", refund.getFSupplierRefund());
            resultMap.put("time", DateUtils.formatYMDHMSDate(refund.getDNewDate()));
        } else if (status.equals("waitconfirmapply")) {
            resultMap.put("status", "waitconfirmapply");
            resultMap.put("tip", "等待云端退款确认");
        } else if (status.equals("denyapply")) {
            MallConfig mallConfig = mallConfigService.getConfigByKey("lRefundDenyApplyTimeOut");
            String sValue = mallConfig.getSValue();
            Integer sNum = Integer.parseInt(sValue);
            resultMap.put("status", "denyapply");
            resultMap.put("tip", "拒绝退款");
            resultMap.put("message", refund.getSDenyApplyReason());
            resultMap.put("time", DateUtils.formatYMDHMSDate(refund.getDDenyApplyDate()));
            resultMap.put("autoclosetime", DateUtils.formatYMDHMSDate(DateUtils.dateAddHours(refund.getDDenyApplyDate(), sNum * 24)));
        } else if (status.equals("closed")) {
            resultMap.put("status", "closed");
            resultMap.put("tip", "退款关闭");
            resultMap.put("time", DateUtils.formatYMDHMSDate(refund.getDCompleteDate()));
        } else if (status.equals("return")) {
            resultMap.put("status", "return");
            resultMap.put("tip", "等待退货");
        } else if (status.equals("denyconfirmreceive")) {
            MallConfig mallConfig = mallConfigService.getConfigByKey("lRefundDenyReceiveTimeOut");
            String sValue = mallConfig.getSValue();
            Integer sNum = Integer.parseInt(sValue);

            resultMap.put("status", "denyconfirmreceive");
            resultMap.put("tip", "供应商拒绝收货");
            resultMap.put("message", refund.getSDenyReceiveExplain());
            resultMap.put("time", DateUtils.formatYMDHMSDate(refund.getDDenyReceiveDate()));
            resultMap.put("autoclosetime", DateUtils.formatYMDHMSDate(DateUtils.dateAddHours(refund.getDDenyReceiveDate(), sNum * 24)));
        }

        return resultMap;
    }

    @Override
    public int saveRefund(Refund refund) {
        return refundMapper.saveRefund(refund);
    }

    @Override
    public Refund getRefundBySn(String sn) {
        return refundMapper.getRefundBySn(sn);
    }

    @Override
    public void updateRefund(Refund refund) {
        refundMapper.updateRefund(refund);
    }

    @Override
    public void updateOrderRefund(String orderSn, Integer pid) {
//        List<Order> orderList = orderService.getOrderBySn(orderSn);
//        Order order = orderList.get(0);
        Map<String, Object> detailMap = new HashMap<>();
        detailMap.put("sClientSN", orderSn);
        detailMap.put("ProductID", pid);

        OrderDetail orderDetail = orderDetailService.getDetailByClientSnAndPID(detailMap);

        if (orderDetail == null) {
            throw new ExceptionUtil(StatusEnum.ORDER_NOT_EXIST);
        }

        orderDetail.setStatusID("refunding");
        orderDetailService.updateRefundStatus(orderDetail);

        Order order = orderService.getOrderByID(orderDetail.getOrderID());
        order.setRefundStatusID("refunding");
        orderService.updateRefundStatus(order);
    }

    @Override
    public void closeRefund(Refund refund) {
        refundMapper.closeRefund(refund);
    }

    @Transactional
    @Override
    public StatusEnum refundCancel(String sn, String ordersn, Integer pID) {
        //更改refund表状态
        //添加refundlog记录
        //改变orderdetail
        //改变order
        //改变refundreturn

        Refund refund = this.getRefundBySn(sn);
        if (refund == null) {
            Map<String, Object> detailMap = new HashMap<>();
            detailMap.put("sClientSN", ordersn);
            detailMap.put("ProductID", pID);
            OrderDetail orderDetail = orderDetailService.getDetailByClientSnAndPID(detailMap);
            if (orderDetail == null) {
                throw new ExceptionUtil(StatusEnum.REFUND_NOT_EXIST);
            } else {
                orderDetail.setStatusID(null);
                orderDetailService.updateRefundStatus(orderDetail);

                //2019年5月30日
                this.updateOrderRefundStatus(orderDetail.getOrderID());

                return StatusEnum.SUCCESS;
            }
        }

        Integer orderID = refund.getOrderID();
        Integer orderDetailID = refund.getOrderDetailID();
        Integer refundID = refund.getLID();
        String typeID = refund.getTypeID();

        Date nowtime = new Date();
        refund.setDCompleteDate(nowtime);
        refund.setStatusID("closed");

        refundMapper.closeRefund(refund);


        RefundLog refundLog = new RefundLog();
        refundLog.setRefundID(refundID);
        refundLog.setSWhoDo("买家");
        refundLog.setStatusID("closed");

        Map<String, Object> reasonMap = new HashMap<>();
        reasonMap.put("关闭原因", "买家撤销申请");
        refundLog.setSMessage(JSON.toJSONString(reasonMap));

        refundLogService.insertRefundLog(refundLog);

        OrderDetail orderDetail = orderDetailService.getOrderDetailByID(orderDetailID);
        orderDetail.setStatusID("closed");
        orderDetail.setDRefundCompleteDate(nowtime);

        orderDetailService.refundClosed(orderDetail);

        this.updateOrderRefundStatus(orderID);

        if (!typeID.equals("onlymoney")) {
            RefundReturn refundReturn = new RefundReturn();
            refundReturn.setStatusID("closed");
            refundReturnService.closeRefund(refundReturn);
        }

        return StatusEnum.SUCCESS;
    }

    private void updateOrderRefundStatus(Integer orderID) {
        List<OrderDetail> detailList = orderDetailService.getOrderDetailByOrderID(orderID);

        Boolean isRefunding = false;
        //判断订单明细是否有在退款中，只要有一单明细退款中，主订单就是退款中
        for (OrderDetail orderDetailInfo : detailList) {
            if (orderDetailInfo.getBRefunding()) {
                isRefunding = true;
                break;
            }
        }

        //不在退款中
        String orderStatus = null;
        if (isRefunding) {
            orderStatus = "refunding";
        }

        Order order = orderService.getOrderByID(orderID);
        order.setRefundStatusID(orderStatus);
        orderService.updateRefundStatus(order);
    }

    @Override
    public BigDecimal computerRefundAmount(Product product, Order order, OrderDetail orderDetail) {
        BigDecimal fTotalPrice = orderDetailService.countRefundProductPrice(orderDetail);
        BigDecimal detailFship = orderDetail.getFShip();
        BigDecimal detailFTotalship = orderDetail.getFBuyerPaidTotal();

        BigDecimal fTotalShip = ArithUtil.mulRounding(detailFship, ArithUtil.divRounding(detailFTotalship, fTotalPrice, 2), 2);
        BigDecimal fRefundReal = ArithUtil.addRounding(fTotalShip, detailFTotalship, 2);

        return fRefundReal;
    }

    @Override
    public void updateRefundStatus(Refund refund) {
        refundMapper.updateRefundStatus(refund);
    }
}
