package group.dny.api.service;

import com.baomidou.mybatisplus.extension.service.IService;
import group.dny.api.component.model.DealFlowModel;
import group.dny.api.entity.DealFlow;
import group.dny.api.utils.ExceptionUtil;

import java.math.BigDecimal;
import java.util.Map;

/**
 * <p>
 * 服务类
 * </p>
 *
 * @author lizhengfan
 * @since 2019-03-28
 */
public interface IDealFlowService extends IService<DealFlow> {
    //订单流数据处理
    void change(DealFlowModel dealFlowModel) throws ExceptionUtil;

    //计算数据
    BigDecimal computeDeal(Map<String, Object> map);

    //获取金额
    BigDecimal getMoneyBySupplierID(DealFlow dealFlow);
}
