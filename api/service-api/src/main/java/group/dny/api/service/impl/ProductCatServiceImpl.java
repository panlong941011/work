package group.dny.api.service.impl;

import com.baomidou.mybatisplus.extension.service.impl.ServiceImpl;
import group.dny.api.entity.ProductCat;
import group.dny.api.mapper.ProductCatMapper;
import group.dny.api.service.IProductCatService;
import org.springframework.stereotype.Service;

/**
 * <p>
 *  服务实现类
 * </p>
 *
 * @author Mars
 * @since 2019-03-14
 */
@Service
public class ProductCatServiceImpl extends ServiceImpl<ProductCatMapper, ProductCat> implements IProductCatService {

}
