package group.dny.api.entity;

import com.baomidou.mybatisplus.annotation.TableField;
import com.baomidou.mybatisplus.annotation.TableId;
import com.baomidou.mybatisplus.annotation.TableName;
import lombok.Data;
import lombok.EqualsAndHashCode;
import lombok.experimental.Accessors;

/**
 * <p>
 *
 * </p>
 *
 * @author lizhengfan
 * @since 2019-04-03
 */
@Data
@EqualsAndHashCode(callSuper = true)
@Accessors(chain = true)
@TableName("ExpressCompany")
public class ExpressCompany extends BaseEntity {

    private static final long serialVersionUID = 1L;

    @TableId("ID")
    private String id;

    @TableField("sName")
    private String sName;

    @TableField("sPinYin")
    private String sPinYin;

    @TableField("sCompanyCode")
    private String sCompanyCode;

    @TableField("bKdBird")
    private String bKdBird;

    @TableField("sKdBirdCode")
    private String sKdBirdCode;


}
