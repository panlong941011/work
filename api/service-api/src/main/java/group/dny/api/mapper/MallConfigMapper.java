package group.dny.api.mapper;

import com.baomidou.mybatisplus.core.mapper.BaseMapper;
import group.dny.api.entity.MallConfig;

/**
 * <p>
 * Mapper 接口
 * </p>
 *
 * @author lizhengfan
 * @since 2019-04-25
 */
public interface MallConfigMapper extends BaseMapper<MallConfig> {
    MallConfig getConfigByKey(String skey);
}
