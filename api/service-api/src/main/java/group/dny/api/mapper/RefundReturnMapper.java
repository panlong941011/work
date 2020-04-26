package group.dny.api.mapper;

import com.baomidou.mybatisplus.core.mapper.BaseMapper;
import group.dny.api.entity.RefundReturn;

/**
 * <p>
 * Mapper 接口
 * </p>
 *
 * @author lizhengfan
 * @since 2019-04-25
 */
public interface RefundReturnMapper extends BaseMapper<RefundReturn> {
    void insertRefundReturn(RefundReturn refundReturn);

    void closeRefund(RefundReturn refundReturn);

    void updateShipInfo(RefundReturn refundReturn);
}
