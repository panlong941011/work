package group.dny.api.entity;

import com.baomidou.mybatisplus.annotation.IdType;
import com.baomidou.mybatisplus.annotation.TableField;
import com.baomidou.mybatisplus.annotation.TableId;
import com.baomidou.mybatisplus.annotation.TableName;
import lombok.Data;
import lombok.EqualsAndHashCode;
import lombok.experimental.Accessors;

import java.util.Date;

/**
 * <p>
 *
 * </p>
 *
 * @author lizhengfan
 * @since 2019-04-25
 */
@Data
@EqualsAndHashCode(callSuper = true)
@Accessors(chain = true)
@TableName("RefundReturn")
public class RefundReturn extends BaseEntity {

    private static final long serialVersionUID = 1L;

    @TableId(value = "lID", type = IdType.AUTO)
    private Integer lID;

    @TableField("sName")
    private String sName;

    @TableField("dNewDate")
    private Date dNewDate;

    @TableField("RefundID")
    private Integer RefundID;

    @TableField("StatusID")
    private String StatusID;

    @TableField("sShipVoucher")
    private String sShipVoucher;

    @TableField("ShipCompanyID")
    private String ShipCompanyID;

    @TableField("sShipNo")
    private String sShipNo;

    @TableField("sMobile")
    private String sMobile;

    @TableField("SupplierID")
    private Integer SupplierID;


}
