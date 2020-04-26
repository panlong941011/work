package group.dny.api.service.impl;

import com.alibaba.fastjson.JSONObject;
import com.baomidou.mybatisplus.extension.service.impl.ServiceImpl;
import group.dny.api.entity.IPLocation;
import group.dny.api.mapper.IPLocationMapper;
import group.dny.api.service.IIPLocationService;
import group.dny.api.utils.ExceptionUtil;
import group.dny.api.utils.HttpClientUtil;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;

import java.util.HashMap;
import java.util.Map;

@Service
public class IPLocationServiceImpl extends ServiceImpl<IPLocationMapper, IPLocation> implements IIPLocationService {
    @Autowired
    IPLocationMapper ipLocationMapper;

    @Override
    public void insertIPLocation(IPLocation location) {
        ipLocationMapper.insertIPLocation(location);
    }

    @Override
    public IPLocation getIPLocationByIP(String ip) throws ExceptionUtil {
        IPLocation nativeLocation = ipLocationMapper.getIPLocationByIP(ip);
        if (nativeLocation == null) {
            nativeLocation = this.getIPLocationByHttp(ip);
            this.insertIPLocation(nativeLocation);
        }
        return nativeLocation;
    }

    @Override
    public IPLocation getIPLocationByHttp(String ip) {
        IPLocation ipLocation = null;
        Map<String, String> params = new HashMap();
        params.put("ip", ip);
        params.put("output", "json");
        params.put("key", "9c06a9c48f3d3efd77721ba0a05f2d83");

        String url = "https://restapi.amap.com/v3/ip";

        String info = HttpClientUtil.doPost(url, params);
        JSONObject jsonObject = JSONObject.parseObject(info);
        if (jsonObject != null && jsonObject.get("status").equals("1")) {
            ipLocation = new IPLocation();
            ipLocation.setSIP(ip);
            ipLocation.setSProvince((String) jsonObject.get("province"));
            ipLocation.setSCity((String) jsonObject.get("city"));
            ipLocation.setSRectangle((String) jsonObject.get("rectangle"));
        }

        return ipLocation;
    }
}
