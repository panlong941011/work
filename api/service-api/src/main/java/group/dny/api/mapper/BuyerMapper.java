package group.dny.api.mapper;

import com.baomidou.mybatisplus.core.mapper.BaseMapper;
import group.dny.api.entity.Buyer;

/**
 * <p>
 * Mapper 接口
 * </p>
 *
 * @author lizhengfan
 * @since 2019-03-21
 */
public interface BuyerMapper extends BaseMapper<Buyer> {
    Buyer findBuyerByIp(Buyer buyer);

    Buyer findBuyerByID(Integer ID);

    void updateBuyerBalanceByID(Buyer buyer);

    Buyer findBuyerByAppID(Buyer buyer);
}
