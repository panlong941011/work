package group.dny.api.component.model;

import lombok.Data;
import lombok.experimental.Accessors;

import java.math.BigDecimal;

@Data
@Accessors(chain = true)
public class DealFlowModel {
    private String sName;
    private BigDecimal fMoney;
    private Integer memberID;
    private String roleType;
    private String typeID;
    private Integer dealID;
    private String order;
}
