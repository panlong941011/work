package group.dny.api.mapper;

import com.baomidou.mybatisplus.core.mapper.BaseMapper;
import group.dny.api.entity.DealFlow;

import java.math.BigDecimal;
import java.util.Map;

/**
 * <p>
 * Mapper 接口
 * </p>
 *
 * @author lizhengfan
 * @since 2019-03-28
 */
public interface DealFlowMapper extends BaseMapper<DealFlow> {
    BigDecimal computeDeal(Map<String, Object> map);

    BigDecimal getMoneyBySupplierID(DealFlow dealFlow);
}
