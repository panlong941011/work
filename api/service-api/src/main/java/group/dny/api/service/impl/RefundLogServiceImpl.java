package group.dny.api.service.impl;

import com.baomidou.mybatisplus.extension.service.impl.ServiceImpl;
import group.dny.api.entity.RefundLog;
import group.dny.api.mapper.RefundLogMapper;
import group.dny.api.service.IRefundLogService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;

/**
 * <p>
 * 服务实现类
 * </p>
 *
 * @author lizhengfan
 * @since 2019-04-24
 */
@Service
public class RefundLogServiceImpl extends ServiceImpl<RefundLogMapper, RefundLog> implements IRefundLogService {
    @Autowired
    RefundLogMapper refundLogMapper;

    @Override
    public int insertRefundLog(RefundLog refundLog) {
        return refundLogMapper.insertRefundLog(refundLog);
    }
}
