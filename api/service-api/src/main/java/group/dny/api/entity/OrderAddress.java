package group.dny.api.entity;

import com.baomidou.mybatisplus.annotation.IdType;
import com.baomidou.mybatisplus.annotation.TableField;
import com.baomidou.mybatisplus.annotation.TableId;
import com.baomidou.mybatisplus.annotation.TableName;
import lombok.Data;
import lombok.EqualsAndHashCode;
import lombok.experimental.Accessors;

import java.time.LocalDateTime;

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
@TableName("OrderAddress")
public class OrderAddress extends BaseEntity {

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

    @TableField("OrderID")
    private Integer OrderID;

    @TableField("ProvinceID")
    private String ProvinceID;

    @TableField("CityID")
    private String CityID;

    @TableField("AreaID")
    private String AreaID;

    @TableField("sAddress")
    private String sAddress;

    @TableField("sMobile")
    private String sMobile;


}
