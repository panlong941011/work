package group.dny.api.service;

import com.baomidou.mybatisplus.core.mapper.BaseMapper;
import com.baomidou.mybatisplus.extension.service.IService;
import group.dny.api.entity.Area;
import group.dny.api.entity.ShipTemplateDetail;

/**
 * @author Mars
 * @since 2019-03-14
 */
public interface IShipTemplateDetailService extends IService<ShipTemplateDetail> {
    //通过订单模板查询模板详情
    ShipTemplateDetail  getShipTemplateDetail(ShipTemplateDetail shipTemplateDetail);
}
