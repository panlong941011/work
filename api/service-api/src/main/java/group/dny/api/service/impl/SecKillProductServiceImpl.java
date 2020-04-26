package group.dny.api.service.impl;

import com.baomidou.mybatisplus.extension.service.impl.ServiceImpl;
import group.dny.api.entity.SecKillProduct;
import group.dny.api.mapper.SecKillProductMapper;
import group.dny.api.service.ISecKillProductService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;

/**
 * <p>
 * 服务实现类
 * </p>
 *
 * @author lizhengfan
 * @since 2019-03-27
 */
@Service
public class SecKillProductServiceImpl extends ServiceImpl<SecKillProductMapper, SecKillProduct> implements ISecKillProductService {
    @Autowired
    SecKillProductMapper secKillProductMapper;

    @Override
    public SecKillProduct getSecKillByPID(Integer pID) {
        return secKillProductMapper.getSecKillByPID(pID);
    }
}
