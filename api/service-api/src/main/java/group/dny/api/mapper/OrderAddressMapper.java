package group.dny.api.mapper;

import com.baomidou.mybatisplus.core.mapper.BaseMapper;
import group.dny.api.component.model.OrderAddressModel;
import group.dny.api.entity.OrderAddress;

import java.util.List;

/**
 * <p>
 * Mapper 接口
 * </p>
 *
 * @author lizhengfan
 * @since 2019-03-28
 */
public interface OrderAddressMapper extends BaseMapper<OrderAddress> {
    @Override
    int insert(OrderAddress entity);

    void updateOrderAddress(OrderAddress entity);

    OrderAddress getOrderAddressByID(Integer id);

    List<OrderAddressModel> getOrderAddressList();

    void updateOrderAddressArea(OrderAddress area);
}
