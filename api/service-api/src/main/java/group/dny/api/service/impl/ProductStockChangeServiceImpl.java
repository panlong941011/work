package group.dny.api.service.impl;

import com.baomidou.mybatisplus.extension.service.impl.ServiceImpl;
import group.dny.api.component.constant.StatusEnum;
import group.dny.api.component.model.ProductSkuModel;
import group.dny.api.entity.Product;
import group.dny.api.entity.ProductSKU;
import group.dny.api.entity.ProductStockChange;
import group.dny.api.mapper.ProductMapper;
import group.dny.api.mapper.ProductSKUMapper;
import group.dny.api.mapper.ProductStockChangeMapper;
import group.dny.api.service.IAccessTokenService;
import group.dny.api.service.IProductSKUService;
import group.dny.api.service.IProductService;
import group.dny.api.service.IProductStockChangeService;
import group.dny.api.utils.ExceptionUtil;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;

import java.util.Date;
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
public class ProductStockChangeServiceImpl extends ServiceImpl<ProductStockChangeMapper, ProductStockChange> implements IProductStockChangeService {
    @Autowired
    ProductStockChangeMapper productStockChangeMapper;

    @Autowired
    IProductService productService;

    @Autowired
    IProductSKUService productSKUService;

    @Autowired
    ProductSKUMapper productSKUMapper;

    @Autowired
    ProductMapper productMapper;

    @Autowired
    IAccessTokenService accessTokenService;

    @Override
    public int insert(ProductStockChange entity) {
        return productStockChangeMapper.insert(entity);
    }

    @Override
    public String insertProductStockChangeRecord(List<ProductSkuModel> list, String sn, Integer buyerID) throws ExceptionUtil {
        ProductStockChange productStockChange = null;
        for (ProductSkuModel view : list) {
            productStockChange = new ProductStockChange();
            Integer pID = view.getPID();
            Product product = productService.getProductById(pID);

            String sku = view.getSku();

            Integer lChangeBefore = null;
            Integer lNum = view.getNum();

            ProductSKU productSKU = new ProductSKU();
            productSKU.setSValue(sku);
            productSKU.setProductID(pID);
            ProductSKU productSKUInfo = productSKUService.getProducetSkuByPIDAndSku(productSKU);

            if (productSKUInfo != null && sku.length() > 0) {
                Integer currentStock = productSKUInfo.getLStock();
                Integer needStock = lNum;
                Integer lChangeAfter = currentStock - needStock;
                productSKUInfo.setLStock(lChangeAfter);
                productSKUMapper.updateSKUStockByID(productSKUInfo);

                lChangeBefore = productSKUInfo.getLStock();
            } else {
                lChangeBefore = product.getLStock();
            }

            Integer pCurrentStock = product.getLStock();
            Integer pNeedStock = lNum;

            Integer pLChangeAfter = 0;
            if (pCurrentStock < pNeedStock) {
                throw new ExceptionUtil(StatusEnum.PRODUCT_LOW_STOCK);
            } else {
                pLChangeAfter = pCurrentStock - pNeedStock;
            }

            product.setLStock(pLChangeAfter);
            productService.updateStockByID(product);

            productStockChange.setSName(sn);
            productStockChange.setDNewDate(new Date());
            productStockChange.setProductID(pID);
            productStockChange.setSSKU(sku);
            productStockChange.setLChange(pNeedStock);
            productStockChange.setLChangeAfter(pLChangeAfter);
            productStockChange.setLChangeBefore(lChangeBefore);
            productStockChange.setBuyerID(buyerID);

            productStockChangeMapper.insert(productStockChange);
        }
        return sn;
    }

    @Override
    public List<ProductStockChange> getProductStockChangeListBySn(String sn) {
        return productStockChangeMapper.getProductStockChangeListBySn(sn);
    }

    @Override
    public void updateOrderIDBySn(ProductStockChange productStockChange) {
        productStockChangeMapper.updateOrderIDBySn(productStockChange);
    }
}
