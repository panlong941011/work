package group.dny.api.entity;

import group.dny.api.entity.BaseEntity;
import com.baomidou.mybatisplus.annotation.TableName;
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
@TableName("Area")
public class Area extends BaseEntity {

    private static final long serialVersionUID = 1L;

    @TableId("ID")
    private String id;

    @TableField("sName")
    private String sName;

    @TableField("UpID")
    private String UpID;

    @TableField("sType")
    private String sType;

    @TableField("bActive")
    private Integer bActive;

    @TableField("sPostCode")
    private String sPostCode;


}
