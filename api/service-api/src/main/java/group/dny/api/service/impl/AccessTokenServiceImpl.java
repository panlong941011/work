package group.dny.api.service.impl;

import com.baomidou.mybatisplus.core.conditions.query.QueryWrapper;
import com.baomidou.mybatisplus.extension.service.impl.ServiceImpl;
import group.dny.api.entity.AccessToken;
import group.dny.api.mapper.AccessTokenMapper;
import group.dny.api.service.IAccessTokenService;
import group.dny.api.utils.DateUtils;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;

import java.util.Date;

/**
 * <p>
 * 服务实现类
 * </p>
 *
 * @author lizhengfan
 * @since 2019-04-01
 */
@Service
public class AccessTokenServiceImpl extends ServiceImpl<AccessTokenMapper, AccessToken> implements IAccessTokenService {

    @Autowired
    AccessTokenMapper accessTokenMapper;

    @Override
    public int insertToken(AccessToken accessToken) {
        return accessTokenMapper.insertToken(accessToken);
    }

    @Override
    public AccessToken getAccessTokenByToken(String token) {
        return accessTokenMapper.getAccessTokenByToken(token);
    }

    @Override
    public AccessToken getAccessTokenByBuerID(Integer ID) {
        return accessTokenMapper.getAccessTokenByBuerID(ID);
    }

    @Override
    public void delAccessTokenEachWeek() {
        //删除一周之前的数据
        Date nowTime = new Date();
        Date weekBefore = DateUtils.dateAddWeeks(nowTime, -1);

        QueryWrapper<AccessToken> accessTokenWrapper = new QueryWrapper<>();
        accessTokenWrapper.lt("dNewDate", weekBefore);
        accessTokenMapper.delAccessTokenEachWeek(accessTokenWrapper);
    }

    @Override
    public void updateAccessDateByToken(AccessToken accessToken) {
        accessTokenMapper.updateAccessDateByToken(accessToken);
    }
}
