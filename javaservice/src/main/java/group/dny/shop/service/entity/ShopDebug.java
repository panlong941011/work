package group.dny.shop.service.entity;

import com.baomidou.mybatisplus.annotation.TableField;
import com.baomidou.mybatisplus.annotation.TableId;
import com.baomidou.mybatisplus.annotation.TableName;
import group.dny.shop.service.entity.BaseEntity;
import lombok.Data;
import lombok.EqualsAndHashCode;
import lombok.experimental.Accessors;

import java.time.LocalDateTime;

/**
 * <p>
 * 
 * </p>
 *
 * @author Mars
 * @since 2019-03-26
 */
@Data
@EqualsAndHashCode(callSuper = true)
@Accessors(chain = true)
@TableName("ShopDebug")
public class ShopDebug extends BaseEntity {

    private static final long serialVersionUID = 1L;

    @TableId("ID")
    private String id;

    @TableField("sName")
    private String sName;

    @TableField("MemberID")
    private Integer MemberID;

    @TableField("SessionID")
    private String SessionID;

    @TableField("dNewDate")
    private LocalDateTime dNewDate;

    @TableField("bAjax")
    private Integer bAjax;

    @TableField("sMethod")
    private String sMethod;

    @TableField("sIP")
    private String sIP;

    @TableField("sStatusCode")
    private String sStatusCode;

    @TableField("lSQLCount")
    private Integer lSQLCount;

    @TableField("lMailCount")
    private Integer lMailCount;

    @TableField("sSavePath")
    private String sSavePath;

    @TableField("lTimeUse")
    private Integer lTimeUse;


}
