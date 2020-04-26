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
 * @since 2019-04-25
 */
@Data
@EqualsAndHashCode(callSuper = true)
@Accessors(chain = true)
@TableName("MallConfig")
public class MallConfig extends BaseEntity {

    private static final long serialVersionUID = 1L;

    @TableId("sKey")
    private String sKey;

    @TableField("sValue")
    private String sValue;

    @TableField("sNote")
    private String sNote;


}
