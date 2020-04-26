package group.dny.api.mapper;

import com.baomidou.mybatisplus.core.conditions.query.QueryWrapper;
import com.baomidou.mybatisplus.core.mapper.BaseMapper;
import com.baomidou.mybatisplus.core.metadata.IPage;
import com.baomidou.mybatisplus.extension.plugins.pagination.Page;
import group.dny.api.entity.Supplier;
import org.apache.ibatis.annotations.Param;

/**
 * <p>
 * Mapper 接口
 * </p>
 *
 * @author lizhengfan
 * @since 2019-03-25
 */
public interface SupplierMapper extends BaseMapper<Supplier> {
    Supplier getSupplierByID(Integer ID);

    void updateBalanceByID(Supplier supplier);

    void updateUnsettlementByID(Supplier supplier);

    void updateWithdrawByID(Supplier supplier);

    void updateSumIncomeByID(Supplier supplier);

    IPage<Supplier> getSupplierList(Page page, @Param("ew") QueryWrapper<Supplier> supplierWrapper);
}
