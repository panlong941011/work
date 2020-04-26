package group.dny.api.service.impl;

import com.baomidou.mybatisplus.extension.service.impl.ServiceImpl;
import group.dny.api.entity.OrderLogistics;
import group.dny.api.mapper.OrderLogisticsMapper;
import group.dny.api.service.IOrderLogisticsService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;

import java.util.List;

/**
 * <p>
 * 服务实现类
 * </p>
 *
 * @author lizhengfan
 * @since 2019-04-18
 */
@Service
public class OrderLogisticsServiceImpl extends ServiceImpl<OrderLogisticsMapper, OrderLogistics> implements IOrderLogisticsService {
    @Autowired
    OrderLogisticsMapper orderLogisticsMapper;

    @Override
    public List<OrderLogistics> getOrderLogicByOrderID(Integer OrderID) {
        return orderLogisticsMapper.getOrderLogicByOrderID(OrderID);
    }
}
