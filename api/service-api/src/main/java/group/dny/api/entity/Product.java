package group.dny.api.entity;

import com.baomidou.mybatisplus.annotation.IdType;
import com.baomidou.mybatisplus.annotation.TableField;
import com.baomidou.mybatisplus.annotation.TableId;
import com.baomidou.mybatisplus.annotation.TableName;
import lombok.Data;
import lombok.EqualsAndHashCode;
import lombok.experimental.Accessors;

import java.math.BigDecimal;
import java.util.Date;


/**
 * <p>
 *
 * </p>
 *
 * @author Mars
 * @since 2019-03-14
 */
@Data
@EqualsAndHashCode(callSuper = true)
@Accessors(chain = true)
@TableName("ProductEnum")
public class Product extends BaseEntity {

    private static final long serialVersionUID = 1L;

    @TableId(value = "lID", type = IdType.AUTO)
    private Integer lID;

    @TableField("sName")
    private String sName;

    @TableField("OwnerID")
    private Integer OwnerID;

    @TableField("NewUserID")
    private Integer NewUserID;

    @TableField("EditUserID")
    private Integer EditUserID;

    @TableField("dNewDate")
    private Date dNewDate;

    @TableField("dEditDate")
    private Date dEditDate;

    @TableField("sRecomm")
    private String sRecomm;

    @TableField("sCode")
    private String sCode;

    @TableField("ProductBrandID")
    private Integer ProductBrandID;

    @TableField("SupplierID")
    private Integer SupplierID;

    @TableField("ProductCatID")
    private Integer ProductCatID;

    @TableField("PathID")
    private String PathID;

    @TableField("fPrice")
    private BigDecimal fPrice;

    @TableField("lStock")
    private Integer lStock;

    @TableField("bSale")
    private Boolean bSale;

    @TableField("ShipTemplateID")
    private Integer ShipTemplateID;

    @TableField("sContent")
    private String sContent;

    @TableField("lSale")
    private Integer lSale;

    @TableField("sSpec")
    private String sSpec;

    @TableField("sPic")
    private String sPic;

    @TableField("bDel")
    private Boolean bDel;

    @TableField("sMasterPic")
    private String sMasterPic;

    @TableField("FirstTagID")
    private Integer FirstTagID;

    @TableField("fShowPrice")
    private BigDecimal fShowPrice;

    @TableField("fBuyerPrice")
    private BigDecimal fBuyerPrice;

    @TableField("lWeight")
    private Integer lWeight;

    @TableField("fSupplierPrice")
    private BigDecimal fSupplierPrice;

    @TableField("lSaleBase")
    private Integer lSaleBase;

    @TableField("ProductParamTemplateID")
    private Integer ProductParamTemplateID;

    @TableField("sParameterArray")
    private String sParameterArray;

    @TableField("fFreeShipCost")
    private BigDecimal fFreeShipCost;

    @TableField("fShipAdjust")
    private BigDecimal fShipAdjust;

    @TableField("fCostControl")
    private BigDecimal fCostControl;

    @TableField("MemberShipTemplateID")
    private Integer MemberShipTemplateID;

    /**
     * 商品关键字
     */
    @TableField("sKeyword")
    private String sKeyword;

    /**
     * 版本号
     */
    @TableField("VersionID")
    private Integer VersionID;

    /**
     * 更新时间
     */
    @TableField("dUpgradeDate")
    private Date dUpgradeDate;

    /**
     * 来三斤ID
     */
    @TableField("LSJID")
    private Integer lsjid;

    /**
     * 来三斤规格ID
     */
    @TableField("LSJStandardID")
    private Integer LSJStandardID;

    public Boolean getbSaleOut() {
        Boolean isDel = this.getBDel();
        Boolean isSale = this.getBSale();
        Integer stock = this.getLStock();

        if (stock <= 0 && isSale && !isDel) {
            return true;
        } else {
            return false;
        }

    }

}
