package group.dny.api.mapper;

import com.baomidou.mybatisplus.core.mapper.BaseMapper;
import group.dny.api.entity.Refund;

/**
 * <p>
 * Mapper 接口
 * </p>
 *
 * @author lizhengfan
 * @since 2019-04-24
 */
public interface RefundMapper extends BaseMapper<Refund> {
    int saveRefund(Refund refund);

    Refund getRefundBySn(String sn);

    void updateRefund(Refund refund);

    void closeRefund(Refund refund);

    void updateRefundStatus(Refund refund);

}
