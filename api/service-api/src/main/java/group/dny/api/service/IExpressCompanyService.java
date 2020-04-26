package group.dny.api.service;

import com.baomidou.mybatisplus.extension.service.IService;
import group.dny.api.entity.ExpressCompany;

/**
 * <p>
 * 服务类
 * </p>
 *
 * @author lizhengfan
 * @since 2019-04-03
 */
public interface IExpressCompanyService extends IService<ExpressCompany> {
    //根据ID获取快递公司信息
    ExpressCompany getExpressCompanyByID(String id);

    ExpressCompany getIDByCode(String code);
}
