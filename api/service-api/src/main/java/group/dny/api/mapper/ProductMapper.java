package group.dny.api.mapper;

import com.baomidou.mybatisplus.core.conditions.query.QueryWrapper;
import com.baomidou.mybatisplus.core.mapper.BaseMapper;
import com.baomidou.mybatisplus.core.metadata.IPage;
import com.baomidou.mybatisplus.extension.plugins.pagination.Page;
import group.dny.api.entity.Product;
import org.apache.ibatis.annotations.Param;

import java.sql.Wrapper;
import java.util.List;
import java.util.Map;

/**
 * <p>
 * Mapper 接口
 * </p>
 *
 * @author Mars
 * @since 2019-03-14
 */
public interface ProductMapper extends BaseMapper<Product> {
    Product getProductById(@Param("pID") Integer pID);
    IPage<Product> getProductList(Page page, @Param("ew") QueryWrapper<Product> productWrapper);
    Product getStockAndPrice(@Param("pID") Integer pID);

    void updateStockByID(Product product);
}
