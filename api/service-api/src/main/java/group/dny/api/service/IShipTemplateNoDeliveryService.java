package group.dny.api.service;

import com.baomidou.mybatisplus.extension.service.IService;
import group.dny.api.entity.ShipTemplateNoDelivery;

import java.util.List;

public interface IShipTemplateNoDeliveryService extends IService<ShipTemplateNoDelivery> {
    //通过运费模板查询不发送区域信息
    ShipTemplateNoDelivery getShipTemplateNoDeliveryByArea(ShipTemplateNoDelivery shipTemplateNoDelivery);

    //通过模板ID获取不发送区域信息
    List<ShipTemplateNoDelivery> getShipTemplateNoDeliveryByTemplateID(Integer shipTemplateID);
}
