package group.dny.api.mapper;

import com.baomidou.mybatisplus.core.mapper.BaseMapper;
import group.dny.api.entity.ShipTemplate;
import group.dny.api.entity.ShipTemplateDetail;

/**
 * <p>
 * Mapper 接口
 * </p>
 *
 * @author Mars
 * @since 2019-03-14
 */
public interface ShipTemplateDetailMapper extends BaseMapper<ShipTemplateDetail> {
    ShipTemplateDetail  getShipTemplateDetail(ShipTemplateDetail shipTemplateDetail);
}
