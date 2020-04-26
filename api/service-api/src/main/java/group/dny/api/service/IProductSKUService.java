package group.dny.api.service;

import com.baomidou.mybatisplus.extension.service.IService;
import group.dny.api.entity.ProductSKU;

import java.util.List;

/**
 * <p>
 * 服务类
 * </p>
 *
 * @author lizhengfan
 * @since 2019-03-21
 */
public interface IProductSKUService extends IService<ProductSKU> {
    //根据商品ID和SKU信息获取SKU实体
    ProductSKU getProducetSkuByPIDAndSku(ProductSKU sku);

    //根据ID更新SKU库存
    void updateSKUStockByID(ProductSKU sku);

    //根据商品ID获取SKU列表
    List<ProductSKU> getSkuListByPID(Integer pID);

    //根据商品ID和SKU判断是否还有此SKU
    Boolean haveSku(Integer pID, String sku);

    //根据商品ID获取所有的SKU信息
    List getSkuArr(Integer pID);
}
