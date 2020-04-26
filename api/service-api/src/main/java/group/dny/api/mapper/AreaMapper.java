package group.dny.api.mapper;

import com.baomidou.mybatisplus.core.mapper.BaseMapper;
import group.dny.api.entity.Area;

import java.util.List;

/**
 * <p>
 * Mapper 接口
 * </p>
 *
 * @author Mars
 * @since 2019-03-14
 */
public interface AreaMapper extends BaseMapper<Area> {
    Area getAreaByProvince(String province);

    Area getAreaByCity(Area area);

    Area getAreaByID(String ID);

    List<Area> getAreaList(Area area);
}
