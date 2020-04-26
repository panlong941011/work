package group.dny.api.service.impl;

import com.baomidou.mybatisplus.extension.service.impl.ServiceImpl;
import group.dny.api.entity.RefundReturn;
import group.dny.api.mapper.RefundReturnMapper;
import group.dny.api.service.IRefundReturnService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;

/**
 * <p>
 * 服务实现类
 * </p>
 *
 * @author lizhengfan
 * @since 2019-04-25
 */
@Service
public class RefundReturnServiceImpl extends ServiceImpl<RefundReturnMapper, RefundReturn> implements IRefundReturnService {
    @Autowired
    RefundReturnMapper refundReturnMapper;

    @Override
    public void insertRefundReturn(RefundReturn refundReturn) {
        refundReturnMapper.insertRefundReturn(refundReturn);
    }

    @Override
    public void closeRefund(RefundReturn refundReturn) {
        refundReturnMapper.closeRefund(refundReturn);
    }

    @Override
    public void updateShipInfo(RefundReturn refundReturn) {
        refundReturnMapper.updateShipInfo(refundReturn);
    }
}
