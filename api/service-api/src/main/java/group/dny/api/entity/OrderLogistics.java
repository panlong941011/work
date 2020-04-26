package group.dny.api.entity;

import com.baomidou.mybatisplus.annotation.IdType;
import com.baomidou.mybatisplus.annotation.TableField;
import com.baomidou.mybatisplus.annotation.TableId;
import com.baomidou.mybatisplus.annotation.TableName;
import lombok.Data;
import lombok.EqualsAndHashCode;
import lombok.experimental.Accessors;

import java.time.LocalDateTime;
import java.util.Date;

/**
 * <p>
 *
 * </p>
 *
 * @author lizhengfan
 * @since 2019-04-18
 */
@Data
@EqualsAndHashCode(callSuper = true)
@Accessors(chain = true)
@TableName("OrderLogistics")
public class OrderLogistics extends BaseEntity {

    private static final long serialVersionUID = 1L;

    @TableId(value = "lID", type = IdType.AUTO)
    private Integer lID;

    /**
     * 子订单号
     */
    @TableField("sName")
    private String sName;

    /**
     * 商品信息(商品名称，商品数量，商品规格)
     */
    @TableField("sProductInfo")
    private String sProductInfo;

    /**
     * 组成子订单的订单详情ID
     */
    @TableField("sOrderDetailID")
    private String sOrderDetailID;

    /**
     * 订单id
     */
    @TableField("OrderID")
    private Integer OrderID;

    /**
     * 快递单号
     */
    @TableField("sExpressNo")
    private String sExpressNo;

    /**
     * 物流公司
     */
    @TableField("sExpressCompany")
    private String sExpressCompany;

    @TableField("dNewDate")
    private LocalDateTime dNewDate;

    /**
     * 快递鸟回传的html模板
     */
    @TableField("sReturnedTemplate")
    private String sReturnedTemplate;

    /**
     * 面单展示所需数据
     */
    @TableField("sExpressOrderInfo")
    private String sExpressOrderInfo;

    /**
     * 面单状态  1已取单号， 2已打印  3非电子面单
     */
    @TableField("ExpressOrderStatusID")
    private String ExpressOrderStatusID;

    @TableField("SupplierID")
    private Integer SupplierID;

    /**
     * 返回原因
     */
    @TableField("sReason")
    private String sReason;

    @TableField("ShipID")
    private String ShipID;

    @TableField("dShipDate")
    private Date dShipDate;

    @TableField("lChildID")
    private Integer lChildID;


}
