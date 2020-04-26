package group.dny.api.mapper;

import com.baomidou.mybatisplus.core.mapper.BaseMapper;
import group.dny.api.entity.ProductStockChange;

import java.util.List;

/**
 * <p>
 * Mapper 接口
 * </p>
 *
 * @author lizhengfan
 * @since 2019-03-21
 */
public interface ProductStockChangeMapper extends BaseMapper<ProductStockChange> {
    @Override
    int insert(ProductStockChange entity);

    List<ProductStockChange> getProductStockChangeListBySn(String sn);

    void updateOrderIDBySn(ProductStockChange productStockChange);
}
