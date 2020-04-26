package group.dny.api.service;

import com.baomidou.mybatisplus.core.metadata.IPage;
import com.baomidou.mybatisplus.extension.service.IService;
import group.dny.api.entity.Supplier;
import group.dny.api.utils.ExceptionUtil;

/**
 * <p>
 * 服务类
 * </p>
 *
 * @author lizhengfan
 * @since 2019-03-25
 */
public interface ISupplierService extends IService<Supplier> {
    //根据ID获取供应商
    Supplier getSupplierByID(Integer ID);

    //更新金额
    void updateBalanceByID(Supplier supplier);

    //统计供应账户钱包
    void computeAccountMoney(Integer ID);

    //统计待结算金额
    void computeWaitMoney(Integer ID, Supplier supplier);

    //统计已提现金额
    void computeAlreadyMoney(Integer ID, Supplier supplier);

    //统计累积收入
    void sumIncome(Integer ID, Supplier supplier);

    //更新供应商未结算金额
    void updateUnsettlementByID(Supplier supplier);

    //更新供应商提现金额
    void updateWithdrawByID(Supplier supplier);

    //更新供应商总收入
    void updateSumIncomeByID(Supplier supplier);

    //查询供应商列表
    IPage<Supplier> getSupplierList(String keyword, Integer page, Integer pagesize) throws ExceptionUtil;
}
