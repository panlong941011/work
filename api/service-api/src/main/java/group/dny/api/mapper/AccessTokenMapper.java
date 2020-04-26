package group.dny.api.mapper;

import com.baomidou.mybatisplus.core.conditions.query.QueryWrapper;
import com.baomidou.mybatisplus.core.mapper.BaseMapper;
import group.dny.api.entity.AccessToken;
import org.apache.ibatis.annotations.Param;

/**
 * <p>
 * Mapper 接口
 * </p>
 *
 * @author lizhengfan
 * @since 2019-04-01
 */
public interface AccessTokenMapper extends BaseMapper<AccessToken> {
    int insertToken(AccessToken accessToken);

    AccessToken getAccessTokenByToken(String token);

    AccessToken getAccessTokenByBuerID(Integer ID);

    void delAccessTokenEachWeek(@Param("ew") QueryWrapper<AccessToken> accessTokenWrapper);

    void updateAccessDateByToken(AccessToken accessToken);
}
