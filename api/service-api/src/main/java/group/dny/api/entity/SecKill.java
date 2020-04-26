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
 * @since 2019-03-27
 */
@Data
@EqualsAndHashCode(callSuper = true)
@Accessors(chain = true)
@TableName("SecKill")
public class SecKill extends BaseEntity {

    private static final long serialVersionUID = 1L;

    @TableId(value = "lID", type = IdType.AUTO)
    private Integer lID;

    @TableField("sName")
    private String sName;

    @TableField("dNewDate")
    private LocalDateTime dNewDate;

    @TableField("dEditDate")
    private LocalDateTime dEditDate;

    @TableField("NewUserID")
    private Integer NewUserID;

    @TableField("EditUserID")
    private Integer EditUserID;

    @TableField("dStartDate")
    private LocalDateTime dStartDate;

    @TableField("dEndDate")
    private LocalDateTime dEndDate;

    @TableField("sSlogan")
    private String sSlogan;

    @TableField("lNumLimit")
    private Integer lNumLimit;

    @TableField("StatusID")
    private String StatusID;

    @TableField("sProductID")
    private String sProductID;

}
