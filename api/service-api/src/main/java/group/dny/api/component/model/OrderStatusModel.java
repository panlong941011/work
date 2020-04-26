package group.dny.api.component.model;

import java.util.List;

public class OrderStatusModel {
    private Integer pID;
    private String orderStatus;
    private String refundStatus;

    private List<ExpressModel> express;

    public Integer getpID() {
        return pID;
    }

    public void setpID(Integer pID) {
        this.pID = pID;
    }

    public List<ExpressModel> getExpress() {
        return express;
    }

    public void setExpress(List<ExpressModel> express) {
        this.express = express;
    }

    public String getOrderStatus() {
        return orderStatus;
    }

    public void setOrderStatus(String orderStatus) {
        this.orderStatus = orderStatus;
    }

    public String getRefundStatus() {
        return refundStatus;
    }

    public void setRefundStatus(String refundStatus) {
        this.refundStatus = refundStatus;
    }
}
