package group.dny.api.component.model;

import lombok.Data;
import lombok.experimental.Accessors;

import java.math.BigDecimal;
import java.util.List;

@Data
@Accessors(chain = true)
public class OrderTemplateModel {
    private Integer shipTemplateID;
    private Integer weight;
    private BigDecimal fTotal;//销售价
    private Integer supplierID;
    private String supplierStr;
    private String cityID;
    private Integer num;
    private BigDecimal fBuyerTotal;
    private BigDecimal fCostTotal;//成本价
    private List<ProductSkuModel> productList;
    private String sName;
    private Integer buyerID;
    //订单计算需要模板
    private Integer orderShipTemplateID;
}
