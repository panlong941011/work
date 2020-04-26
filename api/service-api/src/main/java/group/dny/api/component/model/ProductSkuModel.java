package group.dny.api.component.model;

import lombok.Data;
import lombok.experimental.Accessors;

@Data
@Accessors(chain = true)
public class ProductSkuModel {
    private String sku;
    private Integer pID;
    private Integer num;
}
