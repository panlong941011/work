package group.dny.api.service.impl;

import com.baomidou.mybatisplus.extension.service.impl.ServiceImpl;
import group.dny.api.entity.PreOrder;
import group.dny.api.mapper.PreOrderMapper;
import group.dny.api.service.IPreOrderService;
import group.dny.api.utils.ExceptionUtil;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;

import java.util.Date;
import java.util.List;

/**
 * <p>
 * 服务实现类
 * </p>
 *
 * @author lizhengfan
 * @since 2019-03-21
 */
@Service
public class PreOrderServiceImpl extends ServiceImpl<PreOrderMapper, PreOrder> implements IPreOrderService {
    @Autowired
    PreOrderMapper preOrderMapper;

    @Override
    public int insert(PreOrder entity) {
        return preOrderMapper.insert(entity);
    }

    @Override
    public List<PreOrder> getPreOrderBySn(String sn) {
        return preOrderMapper.getPreOrderBySn(sn);
    }

    @Override
    public void updatePreOrderAddress(PreOrder preOrder) {
        preOrderMapper.updatePreOrderAddress(preOrder);
    }

    @Override
    public void updateOrderTotal(PreOrder preOrder) {
        preOrderMapper.updateOrderTotal(preOrder);
    }

    @Override
    public void updatePreOrderStatus(String sn, Boolean isCancel) throws ExceptionUtil {
        try {
            PreOrder preOrderData = new PreOrder();
            Date nowtime = new Date();
            preOrderData.setDCloseDate(nowtime);
            preOrderData.setSName(sn);

            if (isCancel) {
                preOrderData.setBClosed(-1);
                preOrderData.setSCloseReason("客户取消");
            } else {
                preOrderData.setBClosed(1);
                preOrderData.setSCloseReason("已扣款");
            }

            preOrderMapper.updatePreOrderStatus(preOrderData);
        } catch (ExceptionUtil e) {
            throw e;
        }
    }
}
