package group.dny.api.service;

import com.baomidou.mybatisplus.extension.service.IService;
import group.dny.api.entity.SecKillProductSKU;

import java.util.List;

/**
 * <p>
 * 服务类
 * </p>
 *
 * @author lizhengfan
 * @since 2019-03-27
 */
public interface ISecKillProductSKUService extends IService<SecKillProductSKU> {
    //根据商品ID获取秒杀商品SKU列表
    List<SecKillProductSKU> getSecKillProductBySecKillID(Integer pID);
}
