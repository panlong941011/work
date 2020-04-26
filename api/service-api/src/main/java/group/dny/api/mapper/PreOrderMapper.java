package group.dny.api.mapper;

import com.baomidou.mybatisplus.core.mapper.BaseMapper;
import group.dny.api.entity.PreOrder;

import java.util.List;

/**
 * <p>
 * Mapper 接口
 * </p>
 *
 * @author lizhengfan
 * @since 2019-03-21
 */
public interface PreOrderMapper extends BaseMapper<PreOrder> {
    @Override
    int insert(PreOrder entity);

    List<PreOrder> getPreOrderBySn(String sn);

    void updatePreOrderAddress(PreOrder preOrder);

    void updateOrderTotal(PreOrder preOrder);

    void updatePreOrderStatus(PreOrder preOrder);
}
