package group.dny.api.service.impl;

import com.baomidou.mybatisplus.extension.service.impl.ServiceImpl;
import group.dny.api.component.model.OrderAddressModel;
import group.dny.api.entity.OrderAddress;
import group.dny.api.mapper.OrderAddressMapper;
import group.dny.api.service.IOrderAddressService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;

import java.util.List;

/**
 * <p>
 * 服务实现类
 * </p>
 *
 * @author lizhengfan
 * @since 2019-03-28
 */
@Service
public class OrderAddressServiceImpl extends ServiceImpl<OrderAddressMapper, OrderAddress> implements IOrderAddressService {
    @Autowired
    OrderAddressMapper orderAddressMapper;

    @Override
    public int insert(OrderAddress entity) {
        return orderAddressMapper.insert(entity);
    }

    @Override
    public void updateOrderAddress(OrderAddress entity) {
        orderAddressMapper.updateOrderAddress(entity);
    }

    @Override
    public OrderAddress getOrderAddressByID(Integer id) {
        return orderAddressMapper.getOrderAddressByID(id);
    }

    @Override
    public List<OrderAddressModel> getOrderAddressList() {
        return orderAddressMapper.getOrderAddressList();
    }

    @Override
    public void updateOrderAddressArea(OrderAddress area) {
        orderAddressMapper.updateOrderAddressArea(area);
    }
}
