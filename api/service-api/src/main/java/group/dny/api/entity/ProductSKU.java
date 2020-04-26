package group.dny.api.entity;

import com.baomidou.mybatisplus.annotation.IdType;
import com.baomidou.mybatisplus.annotation.TableField;
import com.baomidou.mybatisplus.annotation.TableId;
import com.baomidou.mybatisplus.annotation.TableName;
import lombok.Data;
import lombok.EqualsAndHashCode;
import lombok.experimental.Accessors;

import java.math.BigDecimal;

/**
 * <p>
 *
 * </p>
 *
 * @author lizhengfan
 * @since 2019-03-21
 */
@Data
@EqualsAndHashCode(callSuper = true)
@Accessors(chain = true)
@TableName("ProductSKU")
public class ProductSKU extends BaseEntity implements ISkuBase {

    private static final long serialVersionUID = 1L;

    @TableId(value = "lID", type = IdType.AUTO)
    private Integer lID;

    @TableField("ProductID")
    private Integer ProductID;

    @TableField("sValue")
    private String sValue;

    @TableField("fPrice")
    private BigDecimal fPrice;

    @TableField("fCostPrice")
    private BigDecimal fCostPrice;

    @TableField("lStock")
    private Integer lStock;

    @TableField("sCode")
    private String sCode;

    @TableField("fBuyerPrice")
    private BigDecimal fBuyerPrice;

    @Override
    public String getSkuName() {
        return sValue;
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
