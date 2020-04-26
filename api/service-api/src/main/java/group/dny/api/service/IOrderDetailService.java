package group.dny.api.service;

import com.baomidou.mybatisplus.extension.service.IService;
import group.dny.api.entity.OrderDetail;

import java.math.BigDecimal;
import java.util.List;
import java.util.Map;

/**
 * <p>
 * 服务类
 * </p>
 *
 * @author lizhengfan
 * @since 2019-03-28
 */
public interface IOrderDetailService extends IService<OrderDetail> {
    //插入订单详细
    int insert(OrderDetail entity);

    //通过SN获取订单详情
    List<OrderDetail> getOrderStatuByClientSn(String sn);

    OrderDetail getOrderDetailByID(Integer ID);

    List<OrderDetail> getOrderDetailByOrderID(Integer ID);

    OrderDetail getDetailByOrderIDAndPID(OrderDetail orderDetail);

    BigDecimal countProductPrice(OrderDetail orderDetail);

    void updateRefundStatus(OrderDetail orderDetail);

    void refundClosed(OrderDetail orderDetail);

    BigDecimal countRefundProductPrice(OrderDetail orderDetail);

    OrderDetail getDetailByClientSnAndPID(Map<String, Object> map);
}
