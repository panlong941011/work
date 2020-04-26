package group.dny.api.mapper;

import com.baomidou.mybatisplus.core.mapper.BaseMapper;
import group.dny.api.entity.IPLocation;

public interface IPLocationMapper extends BaseMapper<IPLocation> {
    void insertIPLocation(IPLocation location);

    IPLocation getIPLocationByIP(String ip);
}
