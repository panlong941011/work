package group.dny.api.mapper;

import com.baomidou.mybatisplus.core.mapper.BaseMapper;
import group.dny.api.entity.ProductSKU;

import java.util.List;

/**
 * <p>
 * Mapper 接口
 * </p>
 *
 * @author lizhengfan
 * @since 2019-03-21
 */
public interface ProductSKUMapper extends BaseMapper<ProductSKU> {
    ProductSKU getProducetSkuByPIDAndSku(ProductSKU sku);

    void updateSKUStockByID(ProductSKU sku);

    List<ProductSKU> getSkuListByPID(Integer pID);
}
