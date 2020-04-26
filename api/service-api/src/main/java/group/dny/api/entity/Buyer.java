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
 * @since 2019-03-21
 */
@Data
@EqualsAndHashCode(callSuper = true)
@Accessors(chain = true)
@TableName("Buyer")
public class Buyer extends BaseEntity {

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

    @TableField("SysUserID")
    private Integer SysUserID;

    @TableField("sUserName")
    private String sUserName;

    @TableField("sPassword")
    private String sPassword;

    @TableField("sCharger")
    private String sCharger;

    @TableField("sMobile")
    private String sMobile;

    @TableField("sIP")
    private String sIP;

    @TableField("fBalance")
    private BigDecimal fBalance;

    @TableField("sDomainName")
    private String sDomainName;

    @TableField("sCloudShip")
    private String sCloudShip;

    @TableField("sCloudProductOff")
    private String sCloudProductOff;

    @TableField("sCloseOrder")
    private String sCloseOrder;

    @TableField("sReturns")
    private String sReturns;

    @TableField("sServiceTel")
    private String sServiceTel;

    /**
     * 订单物流表推送接口
     */
    @TableField("sDeliveryOrderLogistics")
    private String sDeliveryOrderLogistics;

    @TableField("sAppID")
    private String sAppID;

    @TableField("sAppSec")
    private String sAppSec;

    @TableField("sConfig")
    private String sConfig;

}
