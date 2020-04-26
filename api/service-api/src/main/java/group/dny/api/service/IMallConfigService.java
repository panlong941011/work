package group.dny.api.service;

import com.baomidou.mybatisplus.extension.service.IService;
import group.dny.api.entity.MallConfig;

/**
 * <p>
 * 服务类
 * </p>
 *
 * @author lizhengfan
 * @since 2019-04-25
 */
public interface IMallConfigService extends IService<MallConfig> {
    MallConfig getConfigByKey(String skey);
}
