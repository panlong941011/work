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
import java.util.List;

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
@TableName("Order")
public class Order extends BaseEntity {

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

    @TableField("SupplierID")
    private Integer SupplierID;

    @TableField("BuyerID")
    private Integer BuyerID;

    @TableField("OrderAddressID")
    private Integer OrderAddressID;

    @TableField("StatusID")
    private String StatusID;

    @TableField("RefundStatusID")
    private String RefundStatusID;

    @TableField("fShip")
    private BigDecimal fShip;

    @TableField("fBuyerProductPaid")
    private BigDecimal fBuyerProductPaid;

    @TableField("fSupplierProductIncome")
    private BigDecimal fSupplierProductIncome;

    @TableField("fProfit")
    private BigDecimal fProfit;

    @TableField("fBuyerRefund")
    private BigDecimal fBuyerRefund;

    @TableField("fSupplierRefund")
    private BigDecimal fSupplierRefund;

    @TableField("fBuyerPaid")
    private BigDecimal fBuyerPaid;

    @TableField("fSupplierIncome")
    private BigDecimal fSupplierIncome;

    @TableField("dReceiveDate")
    private Date dReceiveDate;

    @TableField("dSignDate")
    private Date dSignDate;

    @TableField("dCloseDate")
    private Date dCloseDate;

    @TableField("sMessage")
    private String sMessage;

    @TableField("sIP")
    private String sIP;

    @TableField("sNameOrderAddressID")
    private String sNameOrderAddressID;

    @TableField("sMobileOrderAddressID")
    private String sMobileOrderAddressID;

    @TableField("sCloseReson")
    private String sCloseReson;

    @TableField("fRefund")
    private BigDecimal fRefund;

    @TableField("sTradeNo")
    private String sTradeNo;

    @TableField("ShipCompanyID")
    private Integer ShipCompanyID;

    @TableField("sShipNo")
    private String sShipNo;

    @TableField("dShipDate")
    private Date dShipDate;

    @TableField("sClientSN")
    private String sClientSN;

    @TableField("exist = false")
    private List<OrderDetail> detailList;

    @TableField("WholesalerID")
    private Integer WholesalerID;

    @TableField("PurchaseID")
    private Integer PurchaseID;

    @TableField("TypeID")
    private String TypeID;
}
