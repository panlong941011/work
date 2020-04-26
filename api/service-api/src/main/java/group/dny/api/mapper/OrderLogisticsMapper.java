package group.dny.api.mapper;

import com.baomidou.mybatisplus.core.mapper.BaseMapper;
import group.dny.api.entity.OrderLogistics;

import java.util.List;

/**
 * <p>
 * Mapper 接口
 * </p>
 *
 * @author lizhengfan
 * @since 2019-04-18
 */
public interface OrderLogisticsMapper extends BaseMapper<OrderLogistics> {
    List<OrderLogistics> getOrderLogicByOrderID(Integer OrderID);
}
