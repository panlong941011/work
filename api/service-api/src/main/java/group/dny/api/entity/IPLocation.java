package group.dny.api.entity;

import com.baomidou.mybatisplus.annotation.TableField;
import com.baomidou.mybatisplus.annotation.TableId;
import com.baomidou.mybatisplus.annotation.TableName;
import lombok.Data;
import lombok.EqualsAndHashCode;
import lombok.experimental.Accessors;

@Data
@EqualsAndHashCode(callSuper = true)
@Accessors(chain = true)
@TableName("IPLocation")
public class IPLocation extends BaseEntity {

    private static final long serialVersionUID = 1L;

    @TableId("sIP")
    private String sIP;

    @TableField("sProvince")
    private String sProvince;

    @TableField("sCity")
    private String sCity;

    @TableField("sRectangle")
    private String sRectangle;


}
