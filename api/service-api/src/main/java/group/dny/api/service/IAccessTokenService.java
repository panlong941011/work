package group.dny.api.service;

import com.baomidou.mybatisplus.extension.service.IService;
import group.dny.api.entity.AccessToken;

/**
 * <p>
 * 服务类
 * </p>
 *
 * @author lizhengfan
 * @since 2019-04-01
 */
public interface IAccessTokenService extends IService<AccessToken> {
    //插入token
    int insertToken(AccessToken accessToken);

    //根据token获取信息
    AccessToken getAccessTokenByToken(String token);

    //根据buyerID获取信息
    AccessToken getAccessTokenByBuerID(Integer ID);

    //删除accesstoken
    void delAccessTokenEachWeek();

    //更新token有效期
    void updateAccessDateByToken(AccessToken accessToken);
}
