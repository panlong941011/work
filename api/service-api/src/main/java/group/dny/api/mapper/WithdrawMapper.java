package group.dny.api.mapper;

import com.baomidou.mybatisplus.core.mapper.BaseMapper;
import group.dny.api.entity.Withdraw;

import java.math.BigDecimal;

/**
 * <p>
 * Mapper 接口
 * </p>
 *
 * @author lizhengfan
 * @since 2019-03-29
 */
public interface WithdrawMapper extends BaseMapper<Withdraw> {
    BigDecimal getWithdrawmonyBySupplierID(Integer supplierID);
}
