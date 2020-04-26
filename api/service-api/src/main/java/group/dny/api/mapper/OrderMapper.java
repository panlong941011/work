package group.dny.api.mapper;

import com.baomidou.mybatisplus.core.mapper.BaseMapper;
import group.dny.api.entity.Order;

import java.math.BigDecimal;
import java.util.List;

/**
 * <p>
 * Mapper 接口
 * </p>
 *
 * @author lizhengfan
 * @since 2019-03-21
 */
public interface OrderMapper extends BaseMapper<Order> {
    int insertOrder(Order entity);

    void updateOrderAddressByID(Order order);

    Order getOrderByID(Integer ID);

    void updateOrderStatusByID(Order order);

    BigDecimal getWaitMoneyBySupplierID(Integer supplierID);

    List<Order> getOrderBySn(String sn);

    void updateOrderReceiveInfo(Order order);

    void updateRefundStatus(Order order);
}
