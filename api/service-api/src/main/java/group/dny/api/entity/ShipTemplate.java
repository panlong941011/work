package group.dny.api.entity;

import group.dny.api.entity.BaseEntity;
import com.baomidou.mybatisplus.annotation.TableName;
import com.baomidou.mybatisplus.annotation.IdType;
import com.baomidou.mybatisplus.annotation.TableId;
import java.time.LocalDateTime;
import com.baomidou.mybatisplus.annotation.TableField;
import lombok.Data;
import lombok.EqualsAndHashCode;
import lombok.experimental.Accessors;
import com.alibaba.fastjson.annotation.JSONField;

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
@TableName("ShipTemplate")
public class ShipTemplate extends BaseEntity {

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

    @TableField("CountryID")
    private String CountryID;

    @TableField("ProvinceID")
    private String ProvinceID;

    @TableField("CityID")
    private String CityID;

    @TableField("AreaID")
    private String AreaID;

    @TableField("sShipMethod")
    private String sShipMethod;

    @TableField("sValuation")
    private String sValuation;

    @TableField("bSetFree")
    private Integer bSetFree;

    @TableField("sFreeTypeJson")
    private String sFreeTypeJson;

    @TableField("sConsignDateJson")
    private String sConsignDateJson;

    @TableField("sDeliveryJson")
    private String sDeliveryJson;

    @TableField("sDeliveryAddressJson")
    private String sDeliveryAddressJson;

    @TableField("bBearFreight")
    private Integer bBearFreight;

    @TableField("sProductFrom")
    private String sProductFrom;

    @TableField("lProductUse")
    private Integer lProductUse;

    /**
     * 来三斤运费模板ID
     */
    @TableField("LSJID")
    private Integer lsjid;


}
