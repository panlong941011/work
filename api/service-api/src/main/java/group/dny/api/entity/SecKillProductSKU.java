package group.dny.api.entity;

import com.baomidou.mybatisplus.annotation.IdType;
import com.baomidou.mybatisplus.annotation.TableField;
import com.baomidou.mybatisplus.annotation.TableId;
import com.baomidou.mybatisplus.annotation.TableName;
import lombok.Data;
import lombok.EqualsAndHashCode;
import lombok.experimental.Accessors;

import java.math.BigDecimal;
import java.util.List;

/**
 * <p>
 *
 * </p>
 *
 * @author lizhengfan
 * @since 2019-03-27
 */
@Data
@EqualsAndHashCode(callSuper = true)
@Accessors(chain = true)
@TableName("SecKillProductSKU")
public class SecKillProductSKU extends BaseEntity implements ISkuBase {

    private static final long serialVersionUID = 1L;

    @TableId(value = "lID", type = IdType.AUTO)
    private Integer lID;

    @TableField("sName")
    private String sName;

    @TableField("SecKillProductID")
    private Integer SecKillProductID;

    @TableField("SecKillID")
    private Integer SecKillID;

    @TableField("ProductSkuID")
    private Integer ProductSkuID;

    @TableField("fPrice")
    private BigDecimal fPrice;

    @TableField("lStock")
    private Integer lStock;

    @TableField("lSale")
    private Integer lSale;

    @TableField("exist = false")
    private List<OrderDetail> detailList;

    @Override
    public String getSkuName() {
        return sName;
    }

    @Override
    public BigDecimal getSkuPrice() {
        return fPrice;
    }

    @Override
    public Integer getSkuStock() {
        return lStock;
    }
}
