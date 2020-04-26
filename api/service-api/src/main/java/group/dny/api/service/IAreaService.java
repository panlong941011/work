package group.dny.api.service;

import com.baomidou.mybatisplus.extension.service.IService;
import group.dny.api.entity.Area;
import group.dny.api.utils.ExceptionUtil;

import java.util.List;

/**
 * <p>
 * Mapper 接口
 * </p>
 *
 * @author Mars
 * @since 2019-03-14
 */
public interface IAreaService extends IService<Area> {
    //通过省份获取地域信息
    Area getAreaByProvince(String province);

    //通过城市获取地域信息
    Area getAreaByCity(Area area);

    //通过城市获取地域信息
    Area getAreaByArea(Area area);

    Area getAreaByID(String ID);

    List<Area> getAreaList(Area area) throws ExceptionUtil;
}
