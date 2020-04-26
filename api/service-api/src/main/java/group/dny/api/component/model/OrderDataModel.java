package group.dny.api.component.model;

import lombok.Data;
import lombok.experimental.Accessors;

@Data
@Accessors(chain = true)
public class OrderDataModel extends ProductSkuModel {
    private String sName;
    private Integer buyerID;
}
