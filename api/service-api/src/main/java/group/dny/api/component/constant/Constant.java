package group.dny.api.component.constant;

import java.util.HashMap;
import java.util.Map;

public class Constant {
    public static Map<String, String> dealFlowType = new HashMap<String, String>();

    static {
        dealFlowType.put("withdraw", "supplier_withdraw");//供应商提现
        dealFlowType.put("withdraw_deny", "supplier_withdraw_deny");//供应商提现驳回
        dealFlowType.put("income", "supplier_income");//供应商订单收入
        dealFlowType.put("refund", "buyer_refund");//采购商退款
        dealFlowType.put("recharge", "buyer_recharge");//采购商充值
        dealFlowType.put("buy", "buyer_buyproduct");//采购商采购商品
    }
}
