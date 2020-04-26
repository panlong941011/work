package group.dny.api.service.impl;

import com.baomidou.mybatisplus.extension.service.impl.ServiceImpl;
import group.dny.api.component.constant.Constant;
import group.dny.api.component.constant.StatusEnum;
import group.dny.api.component.model.DealFlowModel;
import group.dny.api.entity.Buyer;
import group.dny.api.entity.DealFlow;
import group.dny.api.entity.Supplier;
import group.dny.api.mapper.DealFlowMapper;
import group.dny.api.service.IBuyerService;
import group.dny.api.service.IDealFlowService;
import group.dny.api.service.ISupplierService;
import group.dny.api.utils.ArithUtil;
import group.dny.api.utils.ExceptionUtil;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;

import java.math.BigDecimal;
import java.util.Date;
import java.util.HashMap;
import java.util.Map;

/**
 * <p>
 * 服务实现类
 * </p>
 *
 * @author lizhengfan
 * @since 2019-03-28
 */
@Service
public class DealFlowServiceImpl extends ServiceImpl<DealFlowMapper, DealFlow> implements IDealFlowService {
    @Autowired
    DealFlowMapper dealFlowMapper;

    @Autowired
    IBuyerService buyerService;

    @Autowired
    ISupplierService supplierService;

    @Override
    public void change(DealFlowModel dealFlowModel) throws ExceptionUtil {
        DealFlow dealFlow = new DealFlow();
        dealFlow.setSName(dealFlowModel.getSName());
        BigDecimal fMoney = dealFlowModel.getFMoney();
        String roleType = dealFlowModel.getRoleType();
        if (roleType.equals("buyer")) {
            fMoney = ArithUtil.mulRounding(fMoney, -1, 2);
        }
        dealFlow.setFMoney(fMoney);
        dealFlow.setDNewDate(new Date());
        dealFlow.setTypeID(dealFlowModel.getTypeID());

        String typeID = dealFlowModel.getTypeID();
        Integer dealID = dealFlowModel.getDealID();
        if (typeID.equals(Constant.dealFlowType.get("withdraw"))) {
            dealFlow.setWithdrawID(dealID);
        } else if (typeID.equals(Constant.dealFlowType.get("income"))) {
            dealFlow.setOrderID(dealID);
        } else if (typeID.equals(Constant.dealFlowType.get("refund"))) {
            dealFlow.setRefundID(dealID);
        } else if (typeID.equals(Constant.dealFlowType.get("recharge"))) {
            dealFlow.setRechargeID(dealID);
        } else if (typeID.equals(Constant.dealFlowType.get("buy"))) {
            dealFlow.setOrderID(dealID);
        }

        Map<String, Object> map = new HashMap<>();

        Integer memberID = dealFlowModel.getMemberID();
        map.put("memberID", memberID);
        map.put("roleType", roleType);

        BigDecimal dealBefore = this.computeDeal(map);
        if (dealBefore == null) dealBefore = BigDecimal.valueOf(0);

        dealFlow.setFBalanceBefore(dealBefore);
        BigDecimal dealAfter = ArithUtil.addRounding(dealBefore, fMoney, 2);

        //当为采购商时候，判断他的采购款是否足够
        if (roleType.equals("buyer")) {
            if (dealAfter.floatValue() < 0) {
                throw new ExceptionUtil(StatusEnum.BUYER_AMOUNT_NOT_ENOUGH);
            }
        }


        dealFlow.setFBalanceAfter(dealAfter);

        if (roleType.equals("buyer")) {
            dealFlow.setBuyerID(memberID);
        } else if (roleType.equals("supplier")) {
            dealFlow.setSupplierID(memberID);
        }

        dealFlowMapper.insert(dealFlow);

        if (roleType.equals("buyer")) {
            Buyer buyer = buyerService.findBuyerByID(memberID);
            buyer.setFBalance(dealAfter);
            buyerService.updateBuyerBalanceByID(buyer);
        } else if (roleType.equals("supplier")) {
            Supplier supplier = supplierService.getSupplierByID(memberID);
            supplier.setFBalance(dealAfter);
            supplierService.updateBalanceByID(supplier);
        }
    }

    public BigDecimal computeDeal(Map<String, Object> map) {
        return dealFlowMapper.computeDeal(map);
    }

    @Override
    public BigDecimal getMoneyBySupplierID(DealFlow dealFlow) {
        return dealFlowMapper.getMoneyBySupplierID(dealFlow);
    }
}
