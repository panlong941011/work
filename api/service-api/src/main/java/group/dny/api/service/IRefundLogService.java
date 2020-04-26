package group.dny.api.service;

import com.baomidou.mybatisplus.extension.service.IService;
import group.dny.api.entity.RefundLog;

/**
 * <p>
 * 服务类
 * </p>
 *
 * @author lizhengfan
 * @since 2019-04-24
 */
public interface IRefundLogService extends IService<RefundLog> {
    int insertRefundLog(RefundLog refundLog);
}
