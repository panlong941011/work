package group.dny.api.service.impl;

import com.baomidou.mybatisplus.extension.service.impl.ServiceImpl;
import group.dny.api.entity.ShipTemplateNoDelivery;
import group.dny.api.mapper.ShipTemplateNoDeliveryMapper;
import group.dny.api.service.IShipTemplateNoDeliveryService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;

import java.util.List;

@Service
public class ShipTemplateNoDeliveryServiceImpl extends ServiceImpl<ShipTemplateNoDeliveryMapper, ShipTemplateNoDelivery> implements IShipTemplateNoDeliveryService {
    @Autowired
    private ShipTemplateNoDeliveryMapper shipTemplateNoDeliveryMapper;

    @Override
    public ShipTemplateNoDelivery getShipTemplateNoDeliveryByArea(ShipTemplateNoDelivery shipTemplateNoDelivery) {
        return shipTemplateNoDeliveryMapper.getShipTemplateNoDeliveryByArea(shipTemplateNoDelivery);
    }

    @Override
    public List<ShipTemplateNoDelivery> getShipTemplateNoDeliveryByTemplateID(Integer shipTemplateID) {
        return shipTemplateNoDeliveryMapper.getShipTemplateNoDeliveryByTemplateID(shipTemplateID);
    }
}
