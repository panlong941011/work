package group.dny.api.service.impl;

import com.baomidou.mybatisplus.extension.service.impl.ServiceImpl;
import group.dny.api.entity.ISkuBase;
import group.dny.api.entity.ProductSKU;
import group.dny.api.entity.SecKillProduct;
import group.dny.api.mapper.ProductSKUMapper;
import group.dny.api.service.IProductSKUService;
import group.dny.api.service.ISecKillProductSKUService;
import group.dny.api.service.ISecKillProductService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;

import java.util.List;

/**
 * <p>
 * 服务实现类
 * </p>
 *
 * @author lizhengfan
 * @since 2019-03-21
 */
@Service
public class ProductSKUServiceImpl extends ServiceImpl<ProductSKUMapper, ProductSKU> implements IProductSKUService {
    @Autowired
    ProductSKUMapper productSKUMapper;

    @Autowired
    private ISecKillProductService secKillProductService;

    @Autowired
    private ISecKillProductSKUService secKillProductSKUService;

    @Override
    public ProductSKU getProducetSkuByPIDAndSku(ProductSKU sku) {
        return productSKUMapper.getProducetSkuByPIDAndSku(sku);
    }

    @Override
    public void updateSKUStockByID(ProductSKU sku) {
        productSKUMapper.updateSKUStockByID(sku);
    }

    @Override
    public List<ProductSKU> getSkuListByPID(Integer pID) {
        return productSKUMapper.getSkuListByPID(pID);
    }

    @Override
    public Boolean haveSku(Integer pID, String sku) {
        List list = this.getSkuArr(pID);

        if (list != null && list.size() > 0) {
            for (Object obj : list) {
                ISkuBase skuObj = (ISkuBase) obj;
                if (skuObj.getSkuName().equals(sku)) {
                    return true;
                }
            }
        }
        return false;
    }

    @Override
    public List getSkuArr(Integer pID) {
        SecKillProduct secKillProduct = secKillProductService.getSecKillByPID(pID);
        if (secKillProduct != null && secKillProduct.getSStatus().equals("未开始")) {
            return secKillProductSKUService.getSecKillProductBySecKillID(pID);
        } else {
            return this.getSkuListByPID(pID);
        }
    }
}
