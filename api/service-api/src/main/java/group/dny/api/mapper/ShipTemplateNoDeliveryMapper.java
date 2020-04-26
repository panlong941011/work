package group.dny.api.mapper;

import com.baomidou.mybatisplus.core.mapper.BaseMapper;
import group.dny.api.entity.ShipTemplateNoDelivery;

import java.util.List;

/**
 * <p>
 * Mapper 接口
 * </p>
 *
 * @author Mars
 * @since 2019-03-14
 */
public interface ShipTemplateNoDeliveryMapper extends BaseMapper<ShipTemplateNoDelivery> {
    ShipTemplateNoDelivery getShipTemplateNoDeliveryByArea(ShipTemplateNoDelivery shipTemplateNoDelivery);

    List<ShipTemplateNoDelivery> getShipTemplateNoDeliveryByTemplateID(Integer shipTemplateID);
}
