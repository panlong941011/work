package group.dny.api.service.impl;

import com.baomidou.mybatisplus.extension.service.impl.ServiceImpl;
import group.dny.api.entity.Buyer;
import group.dny.api.mapper.BuyerMapper;
import group.dny.api.service.IBuyerService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;

/**
 * <p>
 * 服务实现类
 * </p>
 *
 * @author lizhengfan
 * @since 2019-03-21
 */
@Service
public class BuyerServiceImpl extends ServiceImpl<BuyerMapper, Buyer> implements IBuyerService {
    @Autowired
    private BuyerMapper buyerMapper;

    @Override
    public Buyer findBuyerByIp(Buyer buyer) {
        return buyerMapper.findBuyerByIp(buyer);
    }

    @Override
    public Buyer findBuyerByID(Integer ID) {
        return buyerMapper.findBuyerByID(ID);
    }

    @Override
    public void updateBuyerBalanceByID(Buyer buyer) {
        buyerMapper.updateBuyerBalanceByID(buyer);
    }

    @Override
    public Buyer findBuyerByAppID(Buyer buyer) {
        return buyerMapper.findBuyerByAppID(buyer);
    }
}
