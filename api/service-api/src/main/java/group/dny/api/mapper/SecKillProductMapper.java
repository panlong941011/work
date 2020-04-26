package group.dny.api.mapper;

import com.baomidou.mybatisplus.core.mapper.BaseMapper;
import group.dny.api.entity.SecKillProduct;

/**
 * <p>
 * Mapper 接口
 * </p>
 *
 * @author lizhengfan
 * @since 2019-03-27
 */
public interface SecKillProductMapper extends BaseMapper<SecKillProduct> {
    SecKillProduct getSecKillByPID(Integer pID);
}
