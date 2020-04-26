package group.dny.api.service.impl;

import com.baomidou.mybatisplus.extension.service.impl.ServiceImpl;
import group.dny.api.entity.SecKillProductSKU;
import group.dny.api.mapper.SecKillProductSKUMapper;
import group.dny.api.service.ISecKillProductSKUService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;

import java.util.List;

/**
 * <p>
 * 服务实现类
 * </p>
 *
 * @author lizhengfan
 * @since 2019-03-27
 */
@Service
public class SecKillProductSKUServiceImpl extends ServiceImpl<SecKillProductSKUMapper, SecKillProductSKU> implements ISecKillProductSKUService {

    @Autowired
    SecKillProductSKUMapper secKillProductSKUMapper;

    @Override
    public List<SecKillProductSKU> getSecKillProductBySecKillID(Integer pID) {
        return secKillProductSKUMapper.getSecKillProductBySecKillID(pID);
    }
}
