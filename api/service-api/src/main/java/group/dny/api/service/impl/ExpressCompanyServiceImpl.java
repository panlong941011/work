package group.dny.api.service.impl;

import com.baomidou.mybatisplus.extension.service.impl.ServiceImpl;
import group.dny.api.entity.ExpressCompany;
import group.dny.api.mapper.ExpressCompanyMapper;
import group.dny.api.service.IExpressCompanyService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;

/**
 * <p>
 * 服务实现类
 * </p>
 *
 * @author lizhengfan
 * @since 2019-04-03
 */
@Service
public class ExpressCompanyServiceImpl extends ServiceImpl<ExpressCompanyMapper, ExpressCompany> implements IExpressCompanyService {
    @Autowired
    ExpressCompanyMapper expressCompanyMapper;

    @Override
    public ExpressCompany getExpressCompanyByID(String id) {
        return expressCompanyMapper.getExpressCompanyByID(id);
    }

    @Override
    public ExpressCompany getIDByCode(String code) {
        return expressCompanyMapper.getIDByCode(code);
    }
}
