package group.dny.api.service.impl;

import com.baomidou.mybatisplus.core.conditions.query.QueryWrapper;
import com.baomidou.mybatisplus.core.metadata.IPage;
import com.baomidou.mybatisplus.extension.plugins.pagination.Page;
import com.baomidou.mybatisplus.extension.service.impl.ServiceImpl;
import group.dny.api.component.constant.Constant;
import group.dny.api.component.constant.StatusEnum;
import group.dny.api.entity.DealFlow;
import group.dny.api.entity.Supplier;
import group.dny.api.mapper.SupplierMapper;
import group.dny.api.service.IDealFlowService;
import group.dny.api.service.IOrderService;
import group.dny.api.service.ISupplierService;
import group.dny.api.service.IWithdrawService;
import group.dny.api.utils.ExceptionUtil;
import group.dny.api.utils.NumberUtil;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;

import java.math.BigDecimal;

/**
 * <p>
 * 服务实现类
 * </p>
 *
 * @author lizhengfan
 * @since 2019-03-25
 */
@Service
public class SupplierServiceImpl extends ServiceImpl<SupplierMapper, Supplier> implements ISupplierService {
    @Autowired
    SupplierMapper supplierMapper;

    @Autowired
    IOrderService orderService;

    @Autowired
    IWithdrawService withdrawService;

    @Autowired
    IDealFlowService dealFlowService;

    @Override
    public Supplier getSupplierByID(Integer ID) {
        return supplierMapper.getSupplierByID(ID);
    }

    @Override
    public void updateBalanceByID(Supplier supplier) {
        supplierMapper.updateBalanceByID(supplier);
    }

    @Override
    public void computeAccountMoney(Integer ID) {
        Supplier supplier = supplierMapper.getSupplierByID(ID);
        this.computeWaitMoney(ID, supplier);
        this.computeAlreadyMoney(ID, supplier);
        this.sumIncome(ID, supplier);
    }

    @Override
    public void computeWaitMoney(Integer ID, Supplier supplier) {
        BigDecimal waitMoney = null;
        try {
            waitMoney = orderService.getWaitMoneyBySupplierID(ID);
            if (supplier == null) {
                supplier = supplierMapper.getSupplierByID(ID);
            }
        } catch (RuntimeException e) {
            System.out.println(e.getMessage());
        }

        waitMoney = NumberUtil.nullToZero(waitMoney);
        supplier.setFUnsettlement(waitMoney);
        this.updateUnsettlementByID(supplier);
    }

    @Override
    public void computeAlreadyMoney(Integer ID, Supplier supplier) {
        BigDecimal withdrawMoney = withdrawService.getWithdrawmonyBySupplierID(ID);
        withdrawMoney = NumberUtil.nullToZero(withdrawMoney);
        supplier.setFWithdrawed(withdrawMoney);
        this.updateWithdrawByID(supplier);
    }

    @Override
    public void sumIncome(Integer ID, Supplier supplier) {
        DealFlow dealFlow = new DealFlow();
        dealFlow.setSupplierID(ID);
        dealFlow.setTypeID(Constant.dealFlowType.get("income"));
        BigDecimal fMoney = dealFlowService.getMoneyBySupplierID(dealFlow);
        fMoney = NumberUtil.nullToZero(fMoney);
        supplier.setFSumIncome(fMoney);

        this.updateSumIncomeByID(supplier);
    }

    @Override
    public void updateUnsettlementByID(Supplier supplier) {
        supplierMapper.updateUnsettlementByID(supplier);
    }

    @Override
    public void updateWithdrawByID(Supplier supplier) {
        supplierMapper.updateWithdrawByID(supplier);
    }

    @Override
    public void updateSumIncomeByID(Supplier supplier) {
        supplierMapper.updateSumIncomeByID(supplier);
    }

    @Override
    public IPage<Supplier> getSupplierList(String keyword, Integer currentPage, Integer pageSize) throws ExceptionUtil {
        IPage<Supplier> list = null;
        try {
            Page<Supplier> page = new Page<>(currentPage, pageSize);
            QueryWrapper<Supplier> supplierQueryWrapper = new QueryWrapper<>();
            if (keyword != null && keyword.length() > 0)
                supplierQueryWrapper.like("sName", keyword);
            list = supplierMapper.getSupplierList(page, supplierQueryWrapper);
        } catch (Exception e) {
            throw new ExceptionUtil(StatusEnum.FAILURE);
        }
        return list;
    }
}
