package group.dny.api.service;

import com.baomidou.mybatisplus.extension.service.IService;
import group.dny.api.component.constant.StatusEnum;
import group.dny.api.entity.Product;
import group.dny.api.utils.ExceptionUtil;

import java.math.BigDecimal;
import java.util.List;
import java.util.Map;

/**
 * <p>
 * 服务类
 * </p>
 *
 * @author Mars
 * @since 2019-03-14
 */
public interface IProductService extends IService<Product> {
    //商品分类列表
    Map<String, Object> getCategoryList() throws ExceptionUtil;

    //商品列表
    Map<String, Object> getProductList(Integer page, Integer pageSize, Integer catID) throws ExceptionUtil;

    //根据商品ID获取商品详情
    Product getProductById(Integer pID) throws ExceptionUtil;

    //实时商品库存与价格
    Map<String, Object> getStockAndPrice(Integer pID) throws ExceptionUtil;

    //商品属性变化
    List<Map<String, Object>> getProductChange();

    //商品是否已失效
    StatusEnum bInvalidStatus(Integer pID, String sku, Integer num, String provinceName, String cityName, Boolean isSupplier);

    //根据商品ID更新库存
    void updateStockByID(Product product);

    //获取商品运费
    BigDecimal getShip(Integer num, String ProvinceName, String cityName, Integer shipTemplateID, BigDecimal fTotalMoney, Integer weight) throws ExceptionUtil;
}
