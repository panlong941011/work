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
 * @since 2019-03-21
 */
@Data
@EqualsAndHashCode(callSuper = true)
@Accessors(chain = true)
@TableName("ProductStockChange")
public class ProductStockChange extends BaseEntity {

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

    @TableField("BuyerID")
    private Integer BuyerID;

    @TableField("ProductID")
    private Integer ProductID;

    @TableField("lChange")
    private Integer lChange;

    @TableField("lChangeBefore")
    private Integer lChangeBefore;

    @TableField("lChangeAfter")
    private Integer lChangeAfter;

    @TableField("OrderID")
    private Integer OrderID;

    @TableField("dCloseDate")
    private Date dCloseDate;

    @TableField("sSKU")
    private String sSKU;


}
