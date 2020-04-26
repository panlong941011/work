package group.dny.api.service;

import com.baomidou.mybatisplus.extension.service.IService;
import group.dny.api.entity.RefundReturn;

/**
 * <p>
 * 服务类
 * </p>
 *
 * @author lizhengfan
 * @since 2019-04-25
 */
public interface IRefundReturnService extends IService<RefundReturn> {
    void insertRefundReturn(RefundReturn refundReturn);

    void closeRefund(RefundReturn refundReturn);

    void updateShipInfo(RefundReturn refundReturn);
}
