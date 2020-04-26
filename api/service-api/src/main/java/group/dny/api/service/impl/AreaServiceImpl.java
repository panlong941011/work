package group.dny.api.service.impl;

import com.baomidou.mybatisplus.extension.service.impl.ServiceImpl;
import group.dny.api.component.constant.StatusEnum;
import group.dny.api.entity.Area;
import group.dny.api.mapper.AreaMapper;
import group.dny.api.service.IAreaService;
import group.dny.api.utils.ExceptionUtil;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;

import java.util.List;

@Service
public class AreaServiceImpl extends ServiceImpl<AreaMapper, Area> implements IAreaService {

    @Autowired
    private AreaMapper areaMapper;

    @Override
    public Area getAreaByProvince(String province) {
        //省份名
        //数据库里没有省字
        //判断是否有省字，如果有就去掉

        Area firsArea = areaMapper.getAreaByProvince(province);

        if (firsArea == null) {
            int index = province.indexOf("省");
            if (index > -1) {
                province = province.replace("省", "");
            }

            return areaMapper.getAreaByProvince(province);
        } else {
            return firsArea;
        }
    }

    @Override
    public Area getAreaByCity(Area area) {
        //城市名
        //数据库有市字
        //判断是否有市字，如果没有就加上

        String city = area.getSName();

        //先按原始的查询,如果查不到在加上市字查询

        Area firsArea = areaMapper.getAreaByCity(area);

        if (firsArea == null) {
            int index = city.indexOf("市");
            if (index == -1) {
                city = city + "市";
            }

            area.setSName(city);
            return areaMapper.getAreaByCity(area);
        } else {
            return firsArea;
        }
    }

    @Override
    public Area getAreaByID(String ID) {
        return areaMapper.getAreaByID(ID);
    }

    @Override
    public List<Area> getAreaList(Area area) throws ExceptionUtil {
        List<Area> list = null;
        try {
            list = areaMapper.getAreaList(area);
        } catch (RuntimeException e) {
            throw new ExceptionUtil(StatusEnum.SYS_ERROR);
        }
        return list;
    }

    @Override
    public Area getAreaByArea(Area area) {
        String areaName = area.getSName();

        //先按原始的查询,如果查不到在加上市字查询

        Area firsAreaInfo = areaMapper.getAreaByCity(area);

        if (firsAreaInfo == null) {
            int index = areaName.indexOf("区");
            if (index == -1) {
                areaName = areaName + "区";
            }

            area.setSName(areaName);
            return areaMapper.getAreaByCity(area);
        } else {
            return firsAreaInfo;
        }
    }

}
