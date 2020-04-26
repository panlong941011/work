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
 * @since 2019-03-29
 */
@Data
@EqualsAndHashCode(callSuper = true)
@Accessors(chain = true)
@TableName("Withdraw")
public class Withdraw extends BaseEntity {

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

    @TableField("fMoney")
    private BigDecimal fMoney;

    @TableField("fBalanceBefore")
    private BigDecimal fBalanceBefore;

    @TableField("fBalanceAfter")
    private BigDecimal fBalanceAfter;

    @TableField("sAccountName")
    private String sAccountName;

    @TableField("sAccountNo")
    private String sAccountNo;

    @TableField("sOpenBank")
    private String sOpenBank;

    @TableField("SupplierID")
    private Integer SupplierID;

    @TableField("CheckID")
    private String CheckID;

    @TableField("CheckUserID")
    private Integer CheckUserID;

    @TableField("dCheckDate")
    private LocalDateTime dCheckDate;

    @TableField("sFailReason")
    private String sFailReason;


}
