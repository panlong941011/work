package group.dny.api.service.impl;

import com.baomidou.mybatisplus.extension.service.impl.ServiceImpl;
import group.dny.api.entity.Area;
import group.dny.api.entity.ShipTemplate;
import group.dny.api.mapper.AreaMapper;
import group.dny.api.mapper.ShipTemplateMapper;
import group.dny.api.service.IAreaService;
import group.dny.api.service.IShipTemplateService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;

@Service
public class ShipTemplateServiceImpl extends ServiceImpl<ShipTemplateMapper, ShipTemplate> implements IShipTemplateService {
    @Autowired
    private ShipTemplateMapper shipTemplateMapper;

    @Override
    public ShipTemplate selectById(Integer ID) {
        return shipTemplateMapper.selectById(ID);
    }
}
