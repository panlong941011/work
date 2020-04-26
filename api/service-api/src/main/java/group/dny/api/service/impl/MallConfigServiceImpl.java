package group.dny.api.service.impl;

import com.baomidou.mybatisplus.extension.service.impl.ServiceImpl;
import group.dny.api.entity.MallConfig;
import group.dny.api.mapper.MallConfigMapper;
import group.dny.api.service.IMallConfigService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;

/**
 * <p>
 * 服务实现类
 * </p>
 *
 * @author lizhengfan
 * @since 2019-04-25
 */
@Service
public class MallConfigServiceImpl extends ServiceImpl<MallConfigMapper, MallConfig> implements IMallConfigService {
    @Autowired
    MallConfigMapper mallConfigMapper;

    @Override
    public MallConfig getConfigByKey(String skey) {
        return mallConfigMapper.getConfigByKey(skey);
    }
}
