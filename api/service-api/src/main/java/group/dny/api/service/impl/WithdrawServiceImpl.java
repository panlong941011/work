package group.dny.api.service.impl;

import com.baomidou.mybatisplus.extension.service.impl.ServiceImpl;
import group.dny.api.entity.Withdraw;
import group.dny.api.mapper.WithdrawMapper;
import group.dny.api.service.IWithdrawService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;

import java.math.BigDecimal;

/**
 * <p>
 * 服务实现类
 * </p>
 *
 * @author lizhengfan
 * @since 2019-03-29
 */
@Service
public class WithdrawServiceImpl extends ServiceImpl<WithdrawMapper, Withdraw> implements IWithdrawService {

    @Autowired
    WithdrawMapper withdrawMapper;

    @Override
    public BigDecimal getWithdrawmonyBySupplierID(Integer supplierID) {
        return withdrawMapper.getWithdrawmonyBySupplierID(supplierID);
    }
}
