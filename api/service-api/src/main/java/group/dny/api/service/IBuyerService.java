package group.dny.api.service;

import com.baomidou.mybatisplus.extension.service.IService;
import group.dny.api.entity.Buyer;

/**
 * <p>
 * 服务类
 * </p>
 *
 * @author lizhengfan
 * @since 2019-03-21
 */
public interface IBuyerService extends IService<Buyer> {
    //通过IP获取用户信息
    Buyer findBuyerByIp(Buyer buyer);

    //通过ID获取用户信息
    Buyer findBuyerByID(Integer ID);

    //更新用户金额
    void updateBuyerBalanceByID(Buyer Buyer);

    //通过APPID获取用户信息
    Buyer findBuyerByAppID(Buyer buyer);
}
