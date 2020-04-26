package group.dny.api.mapper;

import com.baomidou.mybatisplus.core.mapper.BaseMapper;
import group.dny.api.entity.OrderDetail;

import java.math.BigDecimal;
import java.util.List;
import java.util.Map;

/**
 * <p>
 * Mapper 接口
 * </p>
 *
 * @author lizhengfan
 * @since 2019-03-28
 */
public interface OrderDetailMapper extends BaseMapper<OrderDetail> {
    @Override
    int insert(OrderDetail entity);

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
