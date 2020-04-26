package group.dny.api.service;

import com.baomidou.mybatisplus.extension.service.IService;
import group.dny.api.entity.SecKillProduct;

/**
 * <p>
 * 服务类
 * </p>
 *
 * @author lizhengfan
 * @since 2019-03-27
 */
public interface ISecKillProductService extends IService<SecKillProduct> {
    //通过商品ID获取秒杀商品
    SecKillProduct getSecKillByPID(Integer pID);
}
