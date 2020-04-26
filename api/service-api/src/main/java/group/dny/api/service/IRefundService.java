package group.dny.api.service;

import com.baomidou.mybatisplus.extension.service.IService;
import group.dny.api.component.constant.StatusEnum;
import group.dny.api.entity.Order;
import group.dny.api.entity.OrderDetail;
import group.dny.api.entity.Product;
import group.dny.api.entity.Refund;

import java.math.BigDecimal;
import java.util.Map;

/**
 * <p>
 * 服务类
 * </p>
 *
 * @author lizhengfan
 * @since 2019-04-24
 */
public interface IRefundService extends IService<Refund> {
    //退款申请
    void refundApply(String sn, String ordersn, Integer pid, Integer itemtotal, Integer refunditem, String type, String reason, String img) throws RuntimeException;

    //退款状态
    Map<String, Object> refundStatus(String sn);

    //保存退款申请
    int saveRefund(Refund refund);

    Refund getRefundBySn(String sn);

    void updateRefund(Refund refund);

    void updateOrderRefund(String orderSn, Integer pid);

    void closeRefund(Refund refund);

    StatusEnum refundCancel(String sn, String ordersn, Integer pID);

    BigDecimal computerRefundAmount(Product product, Order order, OrderDetail orderDetail);

    void updateRefundStatus(Refund refund);
}
