package group.dny.api.entity;

import com.baomidou.mybatisplus.annotation.TableField;
import com.baomidou.mybatisplus.annotation.TableName;
import lombok.Data;
import lombok.EqualsAndHashCode;
import lombok.experimental.Accessors;

import java.util.Date;

/**
 * <p>
 *
 * </p>
 *
 * @author lizhengfan
 * @since 2019-04-01
 */
@Data
@EqualsAndHashCode(callSuper = true)
@Accessors(chain = true)
@TableName("AccessToken")
public class AccessToken extends BaseEntity {

    private static final long serialVersionUID = 1L;

    @TableField("sToken")
    private String sToken;

    @TableField("BuyerID")
    private Integer BuyerID;

    @TableField("dNewDate")
    private Date dNewDate;

    @TableField("dExpireDate")
    private Date dExpireDate;


}
