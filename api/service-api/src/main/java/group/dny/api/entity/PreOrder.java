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
 * @since 2019-03-21
 */
@Data
@EqualsAndHashCode(callSuper = true)
@Accessors(chain = true)
@TableName("PreOrder")
public class PreOrder extends BaseEntity {

    private static final long serialVersionUID = 1L;

    @TableId(value = "lID", type = IdType.AUTO)
    private Integer lID;

    @TableField("sName")
    private String sName;

    @TableField("sProvince")
    private String sProvince;

    @TableField("sCity")
    private String sCity;

    @TableField("sArea")
    private String sArea;

    @TableField("sAddress")
    private String sAddress;

    @TableField("sReceiverName")
    private String sReceiverName;

    @TableField("sMobile")
    private String sMobile;

    @TableField("dNewDate")
    private Date dNewDate;

    @TableField("sMessage")
    private String sMessage;

    @TableField("dCloseDate")
    private Date dCloseDate;

    @TableField("bClosed")
    private Integer bClosed;

    @TableField("sCloseReason")
    private String sCloseReason;

    @TableField("BuyerID")
    private Integer BuyerID;

    @TableField("WholesalerID")
    private Integer WholesalerID;

    @TableField("fTotal")
    private BigDecimal fTotal;

    @TableField("fShip")
    private BigDecimal fShip;

}
