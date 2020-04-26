package group.dny.api.service.impl;

import com.baomidou.mybatisplus.extension.service.impl.ServiceImpl;
import group.dny.api.entity.ShipTemplate;
import group.dny.api.entity.ShipTemplateDetail;
import group.dny.api.mapper.ShipTemplateDetailMapper;
import group.dny.api.mapper.ShipTemplateMapper;
import group.dny.api.service.IShipTemplateDetailService;
import group.dny.api.service.IShipTemplateService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;

@Service
public class ShipTemplateDetailServiceImpl extends ServiceImpl<ShipTemplateDetailMapper, ShipTemplateDetail> implements IShipTemplateDetailService {
    @Autowired
    private ShipTemplateDetailMapper shipTemplateDetailMapper;

    @Override
    public ShipTemplateDetail getShipTemplateDetail(ShipTemplateDetail shipTemplateDetail) {
        return shipTemplateDetailMapper.getShipTemplateDetail(shipTemplateDetail);
    }
}

