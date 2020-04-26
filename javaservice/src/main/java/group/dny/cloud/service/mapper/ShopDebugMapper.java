package group.dny.cloud.service.mapper;

import com.baomidou.mybatisplus.core.mapper.BaseMapper;
import group.dny.cloud.service.entity.ShopDebug;
import org.apache.ibatis.annotations.Delete;

/**
 * <p>
 * Mapper 接口
 * </p>
 *
 * @author Mars
 * @since 2019-03-26
 */
public interface ShopDebugMapper extends BaseMapper<ShopDebug> {

    @Delete("DELETE FROM ShopDebug WHERE dNewDate<DATE_SUB(now(), INTERVAL #{lDay} DAY)")
    int cleanExpire(int lDay);
}
