package group.dny.api.mapper;

import com.baomidou.mybatisplus.core.mapper.BaseMapper;
import group.dny.api.entity.RefundLog;

/**
 * <p>
 * Mapper 接口
 * </p>
 *
 * @author lizhengfan
 * @since 2019-04-24
 */
public interface RefundLogMapper extends BaseMapper<RefundLog> {
    int insertRefundLog(RefundLog refundLog);
}
