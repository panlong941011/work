package group.dny.api.mapper;

import com.baomidou.mybatisplus.core.mapper.BaseMapper;
import group.dny.api.entity.SecKillProductSKU;

import java.util.List;

/**
 * <p>
 * Mapper 接口
 * </p>
 *
 * @author lizhengfan
 * @since 2019-03-27
 */
public interface SecKillProductSKUMapper extends BaseMapper<SecKillProductSKU> {
    List<SecKillProductSKU> getSecKillProductBySecKillID(Integer pID);
}
