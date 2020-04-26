package group.dny.api.entity;

import java.math.BigDecimal;

import group.dny.api.entity.BaseEntity;
import com.baomidou.mybatisplus.annotation.TableName;
import com.baomidou.mybatisplus.annotation.IdType;
import com.baomidou.mybatisplus.annotation.TableId;
import com.baomidou.mybatisplus.annotation.TableField;
import lombok.Data;
import lombok.EqualsAndHashCode;
import lombok.experimental.Accessors;
import com.alibaba.fastjson.annotation.JSONField;

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
@TableName("ShipTemplateDetail")
public class ShipTemplateDetail extends BaseEntity {

    private static final long serialVersionUID = 1L;

    @TableId(value = "lID", type = IdType.AUTO)
    private Integer lID;

    @TableField("ShipTemplateID")
    private Integer ShipTemplateID;

    @TableField("sShipMethod")
    private String sShipMethod;

    @TableField("sFreeExpressType")
    private String sFreeExpressType;

    @TableField("lFreeType")
    private String lFreeType;

    @TableField("sAreaID")
    private String sAreaID;

    @TableField("lStart")
    private BigDecimal lStart;

    @TableField("fPostage")
    private BigDecimal fPostage;

    @TableField("lPlus")
    private BigDecimal lPlus;

    @TableField("fPostageplus")
    private BigDecimal fPostageplus;

    @TableField("sType")
    private String sType;

    @TableField(exist = false)
    private BigDecimal countBuy;
}

