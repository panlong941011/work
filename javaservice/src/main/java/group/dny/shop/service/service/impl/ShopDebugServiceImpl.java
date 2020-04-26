package group.dny.shop.service.service.impl;

import com.baomidou.mybatisplus.extension.service.impl.ServiceImpl;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;
import group.dny.shop.service.entity.ShopDebug;
import group.dny.shop.service.mapper.ShopDebugMapper;
import group.dny.shop.service.service.IShopDebugService;

/**
 * <p>
 *  服务实现类
 * </p>
 *
 * @author Mars
 * @since 2019-03-26
 */
@Service
public class ShopDebugServiceImpl extends ServiceImpl<ShopDebugMapper, ShopDebug> implements IShopDebugService {
    @Autowired
    private ShopDebugMapper mapper;

    /**
     * 清理lDay前的数据
     * @param lDay
     */
    public void cleanExpire(int lDay) {
        mapper.cleanExpire(lDay);
    }
}
