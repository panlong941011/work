package group.dny.api.service;

import com.baomidou.mybatisplus.extension.service.IService;
import group.dny.api.entity.OrderLogistics;

import java.util.List;

/**
 * <p>
 * 服务类
 * </p>
 *
 * @author lizhengfan
 * @since 2019-04-18
 */
public interface IOrderLogisticsService extends IService<OrderLogistics> {
    List<OrderLogistics> getOrderLogicByOrderID(Integer OrderID);
}
