package group.dny.api.service;

import com.baomidou.mybatisplus.extension.service.IService;
import group.dny.api.component.model.OrderAddressModel;
import group.dny.api.entity.OrderAddress;

import java.util.List;

/**
 * <p>
 * 服务类
 * </p>
 *
 * @author lizhengfan
 * @since 2019-03-28
 */
public interface IOrderAddressService extends IService<OrderAddress> {
    //插入订单地址
    int insert(OrderAddress entity);

    //更新订单地址
    void updateOrderAddress(OrderAddress entity);

    OrderAddress getOrderAddressByID(Integer id);

    List<OrderAddressModel> getOrderAddressList();

    void updateOrderAddressArea(OrderAddress area);
}
