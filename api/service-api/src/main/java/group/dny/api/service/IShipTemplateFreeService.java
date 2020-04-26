package group.dny.api.service;

import com.baomidou.mybatisplus.core.mapper.BaseMapper;
import com.baomidou.mybatisplus.extension.service.IService;
import group.dny.api.entity.ShipTemplateDetail;
import group.dny.api.entity.ShipTemplateFree;

/**
 * <p>
 * Mapper 接口
 * </p>
 *
 * @author Mars
 * @since 2019-03-14
 */
public interface IShipTemplateFreeService extends IService<ShipTemplateFree> {
    //通过模板查询免费模板信息
    ShipTemplateFree getShipTemplateFree(ShipTemplateFree shipTemplateFree);
}
