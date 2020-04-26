package group.dny.api.mapper;

import com.baomidou.mybatisplus.core.mapper.BaseMapper;
import group.dny.api.entity.ExpressCompany;

/**
 * <p>
 * Mapper 接口
 * </p>
 *
 * @author lizhengfan
 * @since 2019-04-03
 */
public interface ExpressCompanyMapper extends BaseMapper<ExpressCompany> {
    ExpressCompany getExpressCompanyByID(String id);

    ExpressCompany getIDByCode(String code);
}
