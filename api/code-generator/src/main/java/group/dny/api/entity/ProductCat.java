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
 * @author Mars
 * @since 2019-03-14
 */
@Data
@EqualsAndHashCode(callSuper = true)
@Accessors(chain = true)
@TableName("ProductCat")
public class ProductCat extends BaseEntity {

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

    @TableField("UpID")
    private Integer UpID;

    @TableField("sPathName")
    private String sPathName;

    @TableField("PathID")
    private String PathID;

    @TableField("bNavShow")
    private Boolean bNavShow;

    @TableField("sPic")
    private String sPic;

    @TableField("sPicPath")
    private String sPicPath;

    @TableField("GradeID")
    private String GradeID;

    @TableField("lProductNum")
    private Integer lProductNum;

    @TableField("TopCatID")
    private Integer TopCatID;

    @TableField("SecondCatID")
    private Integer SecondCatID;

    @TableField("lPos")
    private Integer lPos;


}
