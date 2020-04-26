package group.dny.api.service;

import com.baomidou.mybatisplus.extension.service.IService;
import group.dny.api.entity.PreOrder;

import java.util.List;

/**
 * <p>
 * 服务类
 * </p>
 *
 * @author lizhengfan
 * @since 2019-03-21
 */
public interface IPreOrderService extends IService<PreOrder> {
    //插入预订单
    int insert(PreOrder entity);

    //通过订单号获取预订单列表
    List<PreOrder> getPreOrderBySn(String sn);

    //更新预付订单地址
    void updatePreOrderAddress(PreOrder preOrder);

    void updateOrderTotal(PreOrder preOrder);

    void updatePreOrderStatus(String sn, Boolean isCancel);
}
