package group.dny.api.entity;

import com.baomidou.mybatisplus.annotation.IdType;
import com.baomidou.mybatisplus.annotation.TableField;
import com.baomidou.mybatisplus.annotation.TableId;
import com.baomidou.mybatisplus.annotation.TableName;
import group.dny.api.utils.DateUtils;
import lombok.Data;
import lombok.EqualsAndHashCode;
import lombok.experimental.Accessors;

import java.math.BigDecimal;
import java.util.Date;

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
@TableName("SecKillProduct")
public class SecKillProduct extends BaseEntity {

    private static final long serialVersionUID = 1L;

    @TableId(value = "lID", type = IdType.AUTO)
    private Integer lID;

    @TableField("ProductID")
    private Integer ProductID;

    @TableField("SecKillID")
    private Integer SecKillID;

    @TableField("dStartDate")
    private Date dStartDate;

    @TableField("dEndDate")
    private Date dEndDate;

    @TableField("lStock")
    private Integer lStock;

    @TableField("lSale")
    private Integer lSale;

    @TableField("lNumLimit")
    private Integer lNumLimit;

    @TableField("fPrice")
    private BigDecimal fPrice;

    public Boolean getBSaleOut() {
        return lStock <= 0;
    }

    public String getSStatus() {
        Date nowDate = new Date();

        //当前时间小于活动开始时间 未开始
        if (DateUtils.dateCompare(nowDate, dStartDate)) {
            return "未开始";
        } else if (DateUtils.dateCompare(dStartDate, nowDate) && DateUtils.dateCompare(nowDate, dEndDate)) {
            if (this.getBSaleOut()) {
                return "已抢光";
            }
            return "未抢光";
        } else if (DateUtils.dateCompare(nowDate, dEndDate)) {
            if (this.getBSaleOut()) {
                return "已抢光";
            }
            return "已结束";
        } else {
            return "已结束";
        }
    }

}
