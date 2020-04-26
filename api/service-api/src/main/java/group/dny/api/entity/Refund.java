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
 * @author lizhengfan
 * @since 2019-04-24
 */
@Data
@EqualsAndHashCode(callSuper = true)
@Accessors(chain = true)
@TableName("Refund")
public class Refund extends BaseEntity {

    private static final long serialVersionUID = 1L;

    @TableId(value = "lID", type = IdType.AUTO)
    private Integer lID;

    @TableField("sName")
    private String sName;

    @TableField("EditUserID")
    private Integer EditUserID;

    @TableField("dNewDate")
    private Date dNewDate;

    @TableField("dEditDate")
    private Date dEditDate;

    @TableField("SupplierID")
    private Integer SupplierID;

    @TableField("MemberID")
    private Integer MemberID;

    @TableField("StatusID")
    private String StatusID;

    @TableField("TypeID")
    private String TypeID;

    @TableField("OrderID")
    private Integer OrderID;

    @TableField("OrderDetailID")
    private Integer OrderDetailID;

    @TableField("fRefundApply")
    private BigDecimal fRefundApply;

    @TableField("fRefundReal")
    private BigDecimal fRefundReal;

    @TableField("sRefundVoucher")
    private String sRefundVoucher;

    @TableField("sShipNo")
    private String sShipNo;

    @TableField("sAddress")
    private String sAddress;

    @TableField("ShipCompanyID")
    private String ShipCompanyID;

    @TableField("ShipTemplateID")
    private Integer ShipTemplateID;

    @TableField("dShipDate")
    private Date dShipDate;

    @TableField("dAgreeApplyDate")
    private Date dAgreeApplyDate;

    @TableField("dDenyApplyDate")
    private Date dDenyApplyDate;

    @TableField("sDenyApplyReason")
    private String sDenyApplyReason;

    @TableField("sDenyApplyExplain")
    private String sDenyApplyExplain;

    @TableField("dDenyReceiveDate")
    private Date dDenyReceiveDate;

    @TableField("fPaid")
    private BigDecimal fPaid;

    @TableField("dCompleteDate")
    private Date dCompleteDate;

    @TableField("dTransferredDate")
    private Date dTransferredDate;

    @TableField("sShipVoucher")
    private String sShipVoucher;

    @TableField("sDenyReceiveExplain")
    private String sDenyReceiveExplain;

    @TableField("sMobile")
    private String sMobile;

    @TableField("sReason")
    private String sReason;

    @TableField("sExplain")
    private String sExplain;

    @TableField("dAftersaleCheck")
    private Date dAftersaleCheck;

    @TableField("lAftersaleUserID")
    private Integer lAftersaleUserID;

    @TableField("BuyerID")
    private Integer BuyerID;

    @TableField("fBuyerPaidTotal")
    private BigDecimal fBuyerPaidTotal;

    @TableField("fSupplierIncomeTotal")
    private BigDecimal fSupplierIncomeTotal;

    @TableField("fBuyerRefund")
    private BigDecimal fBuyerRefund;

    @TableField("fSupplierRefund")
    private BigDecimal fSupplierRefund;

    @TableField("lRefundItem")
    private Integer lRefundItem;

    @TableField("lItemTotal")
    private Integer lItemTotal;

    @TableField("fRefund")
    private BigDecimal fRefund;

    @TableField("fRefundProduct")
    private BigDecimal fRefundProduct;

    @TableField("fProductPrice")
    private BigDecimal fProductPrice;

    @TableField("fBuyerRefundProduct")
    private BigDecimal fBuyerRefundProduct;

    @TableField("fSupplierRefundProduct")
    private BigDecimal fSupplierRefundProduct;


}
