package group.dny.api.entity;

import com.baomidou.mybatisplus.annotation.IdType;
import com.baomidou.mybatisplus.annotation.TableField;
import com.baomidou.mybatisplus.annotation.TableId;
import com.baomidou.mybatisplus.annotation.TableName;
import group.dny.api.component.constant.StatusEnum;
import lombok.Data;
import lombok.EqualsAndHashCode;
import lombok.experimental.Accessors;

import java.math.BigDecimal;
import java.util.ArrayList;
import java.util.Date;
import java.util.List;

/**
 * <p>
 *
 * </p>
 *
 * @author lizhengfan
 * @since 2019-03-28
 */
@Data
@EqualsAndHashCode(callSuper = true)
@Accessors(chain = true)
@TableName("OrderDetail")
public class OrderDetail extends BaseEntity {

    private static final long serialVersionUID = 1L;

    @TableId(value = "lID", type = IdType.AUTO)
    private Integer lID;

    @TableField("sName")
    private String sName;

    @TableField("OrderID")
    private Integer OrderID;

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

    @TableField("BuyerID")
    private Integer BuyerID;

    @TableField("ProductID")
    private Integer ProductID;

    @TableField("ShipID")
    private String ShipID;

    @TableField("ShipTemplateID")
    private Integer ShipTemplateID;

    @TableField("sShipNo")
    private String sShipNo;

    @TableField("dShipDate")
    private Date dShipDate;

    @TableField("sPic")
    private String sPic;

    @TableField("sSKU")
    private String sSKU;

    @TableField("lQuantity")
    private Integer lQuantity;

    @TableField("fBuyerSalePrice")
    private BigDecimal fBuyerSalePrice;

    @TableField("fBuyerPrice")
    private BigDecimal fBuyerPrice;

    @TableField("fSupplierPrice")
    private BigDecimal fSupplierPrice;

    @TableField("fBuyerPaidTotal")
    private BigDecimal fBuyerPaidTotal;

    @TableField("fSupplierIncomeTotal")
    private BigDecimal fSupplierIncomeTotal;

    @TableField("fBuyerRefund")
    private BigDecimal fBuyerRefund;

    @TableField("fSupplierRefund")
    private BigDecimal fSupplierRefund;

    @TableField("fShip")
    private BigDecimal fShip;

    @TableField("StatusID")
    private String StatusID;

    @TableField("dSignDate")
    private Date dSignDate;

    @TableField("dRefundCompleteDate")
    private Date dRefundCompleteDate;

    @TableField("RefundID")
    private Integer RefundID;

    @TableField("fRefund")
    private BigDecimal fRefund;

    @TableField("ShipCompanyID")
    private String ShipCompanyID;


    public Boolean getBRefunding() {
        String statusID = this.getStatusID();
        List<String> statusList = new ArrayList<>();
        statusList.add(StatusEnum.ORDER_CLOSED.getValue());
        statusList.add(StatusEnum.ORDER_SUCCESS.getValue());
        if (statusID == null || statusList.contains(statusID)) {
            return false;
        }
        return true;
    }

    public Boolean getBShiped() {
        if (this.getDShipDate() != null) {
            return true;
        } else {
            return false;
        }
    }


}
