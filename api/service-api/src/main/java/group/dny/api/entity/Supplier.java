package group.dny.api.entity;

import com.baomidou.mybatisplus.annotation.IdType;
import com.baomidou.mybatisplus.annotation.TableField;
import com.baomidou.mybatisplus.annotation.TableId;
import com.baomidou.mybatisplus.annotation.TableName;
import lombok.Data;
import lombok.EqualsAndHashCode;
import lombok.experimental.Accessors;

import java.math.BigDecimal;
import java.time.LocalDateTime;

/**
 * <p>
 *
 * </p>
 *
 * @author lizhengfan
 * @since 2019-03-25
 */
@Data
@EqualsAndHashCode(callSuper = true)
@Accessors(chain = true)
@TableName("Supplier")
public class Supplier extends BaseEntity {

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
    private LocalDateTime dNewDate;

    @TableField("dEditDate")
    private LocalDateTime dEditDate;

    @TableField("MemberID")
    private Integer MemberID;

    @TableField("SysUserID")
    private Integer SysUserID;

    @TableField("sNote")
    private String sNote;

    @TableField("sUrl")
    private String sUrl;

    @TableField("sPic")
    private String sPic;

    @TableField("sPicPath")
    private String sPicPath;

    @TableField("sContent")
    private String sContent;

    @TableField("sRefundAddress")
    private String sRefundAddress;

    @TableField("lProductNum")
    private Integer lProductNum;

    @TableField("sUserName")
    private String sUserName;

    @TableField("sPassword")
    private String sPassword;

    @TableField("sCharger")
    private String sCharger;

    @TableField("sMobile")
    private String sMobile;

    @TableField("fBalance")
    private BigDecimal fBalance;

    @TableField("fUnsettlement")
    private BigDecimal fUnsettlement;

    @TableField("fWithdrawed")
    private BigDecimal fWithdrawed;

    @TableField("fSumIncome")
    private BigDecimal fSumIncome;

    /**
     * 来三斤ID
     */
    @TableField("LSJID")
    private Integer lsjid;

//    @TableField(exist = false)
//    private List<SupplierParam> shipParam;

    @TableField("BuyerID")
    private Integer BuyerID;
}
