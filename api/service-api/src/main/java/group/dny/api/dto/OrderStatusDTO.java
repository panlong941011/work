package group.dny.api.dto;

import group.dny.api.component.model.ExpressModel;
import lombok.Data;
import lombok.experimental.Accessors;

import java.util.List;

@Data
@Accessors(chain = true)
public class OrderStatusDTO {
    private Integer pID;
    private String status;
    private List<ExpressModel> express;
}
