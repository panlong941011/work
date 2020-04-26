package group.dny.api.entity;

import group.dny.api.entity.BaseEntity;
import com.baomidou.mybatisplus.annotation.TableName;
import com.baomidou.mybatisplus.annotation.IdType;
import com.baomidou.mybatisplus.annotation.TableId;
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
@TableName("ShipTemplateFree")
public class ShipTemplateFree extends BaseEntity {

    private static final long serialVersionUID = 1L;

    @TableId(value = "lID", type = IdType.AUTO)
    private Integer lID;

    @TableField("ShipTemplateID")
    private Integer ShipTemplateID;

    @TableField("sFreeAreaID")
    private String sFreeAreaID;

    @TableField("sFreeShipMethod")
    private String sFreeShipMethod;

    @TableField("sFreeExpressType")
    private String sFreeExpressType;

    @TableField("lFreeType")
    private Integer lFreeType;

    @TableField("fFreeNumber")
    private String fFreeNumber;

    @TableField("fFreeMoney")
    private String fFreeMoney;


}
