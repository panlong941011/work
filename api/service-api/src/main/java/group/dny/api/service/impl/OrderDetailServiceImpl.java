package group.dny.api.service.impl;

import com.baomidou.mybatisplus.extension.service.impl.ServiceImpl;
import group.dny.api.entity.OrderDetail;
import group.dny.api.mapper.OrderDetailMapper;
import group.dny.api.service.IOrderDetailService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;

import java.math.BigDecimal;
import java.util.List;
import java.util.Map;

/**
 * <p>
 * 服务实现类
 * </p>
 *
 * @author lizhengfan
 * @since 2019-03-28
 */
@Service
public class OrderDetailServiceImpl extends ServiceImpl<OrderDetailMapper, OrderDetail> implements IOrderDetailService {
    @Autowired
    OrderDetailMapper orderDetailMapper;

    @Override
    public int insert(OrderDetail entity) {
        return orderDetailMapper.insert(entity);
    }

    @Override
    public List<OrderDetail> getOrderStatuByClientSn(String sn) {
        return orderDetailMapper.getOrderStatuByClientSn(sn);
    }

    @Override
    public OrderDetail getOrderDetailByID(Integer ID) {
        return orderDetailMapper.getOrderDetailByID(ID);
    }

    @Override
    public List<OrderDetail> getOrderDetailByOrderID(Integer ID) {
        return orderDetailMapper.getOrderDetailByOrderID(ID);
    }

    @Override
    public OrderDetail getDetailByOrderIDAndPID(OrderDetail orderDetail) {
        return orderDetailMapper.getDetailByOrderIDAndPID(orderDetail);
    }

    @Override
    public BigDecimal countProductPrice(OrderDetail orderDetail) {
        return orderDetailMapper.countProductPrice(orderDetail);
    }

    @Override
    public void updateRefundStatus(OrderDetail orderDetail) {
        orderDetailMapper.updateRefundStatus(orderDetail);
    }

    @Override
    public void refundClosed(OrderDetail orderDetail) {
        orderDetailMapper.refundClosed(orderDetail);
    }

    @Override
    public BigDecimal countRefundProductPrice(OrderDetail orderDetail) {
        return orderDetailMapper.countRefundProductPrice(orderDetail);
    }

    @Override
    public OrderDetail getDetailByClientSnAndPID(Map<String, Object> map) {
        return orderDetailMapper.getDetailByClientSnAndPID(map);
    }
}
