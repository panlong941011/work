package group.dny.api.service;

import com.baomidou.mybatisplus.extension.service.IService;
import group.dny.api.entity.Withdraw;

import java.math.BigDecimal;

/**
 * <p>
 * 服务类
 * </p>
 *
 * @author lizhengfan
 * @since 2019-03-29
 */
public interface IWithdrawService extends IService<Withdraw> {
    //根据供应商ID获取提现金额
    BigDecimal getWithdrawmonyBySupplierID(Integer supplierID);
}
