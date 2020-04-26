package group.dny.api.service.impl;

import com.baomidou.mybatisplus.extension.service.impl.ServiceImpl;
import group.dny.api.entity.ShipTemplateDetail;
import group.dny.api.entity.ShipTemplateFree;
import group.dny.api.mapper.ShipTemplateDetailMapper;
import group.dny.api.mapper.ShipTemplateFreeMapper;
import group.dny.api.service.IShipTemplateDetailService;
import group.dny.api.service.IShipTemplateFreeService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;

@Service
public class ShipTemplateFreeServiceImpl extends ServiceImpl<ShipTemplateFreeMapper, ShipTemplateFree> implements IShipTemplateFreeService {
    @Autowired
    private ShipTemplateFreeMapper shipTemplateFreeMapper;

    @Override
    public ShipTemplateFree getShipTemplateFree(ShipTemplateFree shipTemplateFree) {
        return shipTemplateFreeMapper.getShipTemplateFree(shipTemplateFree);
    }
}

