package group.dny.api.service;

import com.baomidou.mybatisplus.extension.service.IService;
import group.dny.api.component.model.ProductSkuModel;
import group.dny.api.entity.ProductStockChange;
import group.dny.api.utils.ExceptionUtil;

import java.util.List;

/**
 * <p>
 * 服务类
 * </p>
 *
 * @author lizhengfan
 * @since 2019-03-21
 */
public interface IProductStockChangeService extends IService<ProductStockChange> {
    //插入库存变动记录
    int insert(ProductStockChange entity);

    //根据SKU信息和订单号插入库存变化记录
    String insertProductStockChangeRecord(List<ProductSkuModel> list, String sn, Integer buyerID) throws ExceptionUtil;

    //通过订单号获取库存变化记录
    List<ProductStockChange> getProductStockChangeListBySn(String sn);

    //根据订单号更新库存变动记录的订单号
    void updateOrderIDBySn(ProductStockChange productStockChange);
}
