package group.dny.api.service;

import com.baomidou.mybatisplus.extension.service.IService;
import group.dny.api.entity.Order;
import group.dny.api.entity.ShipTemplate;

public interface IShipTemplateService extends IService<ShipTemplate> {
    //通过模板ID获取模板信息
    ShipTemplate selectById(Integer ID);
}
