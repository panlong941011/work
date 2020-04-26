package group.dny.api.service;

import com.baomidou.mybatisplus.extension.service.IService;
import group.dny.api.entity.IPLocation;
import group.dny.api.utils.ExceptionUtil;

public interface IIPLocationService extends IService<IPLocation> {
    void insertIPLocation(IPLocation location) throws ExceptionUtil;

    IPLocation getIPLocationByIP(String ip) throws ExceptionUtil;

    IPLocation getIPLocationByHttp(String ip) throws ExceptionUtil;
}
