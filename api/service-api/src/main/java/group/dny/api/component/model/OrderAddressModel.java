package group.dny.api.component.model;

/**
 * @Description TODO
 * @ClassName OrderAddressModel
 * @Author lizhengfan
 * @Date 2019/5/28 14:34
 * @Version 1.0.0
 **/
public class OrderAddressModel {
    private Integer lID;
    private Integer OrderID;
    private String sArea;
    private String ID;

    public Integer getlID() {
        return lID;
    }

    public void setlID(Integer lID) {
        this.lID = lID;
    }

    public Integer getOrderID() {
        return OrderID;
    }

    public void setOrderID(Integer orderID) {
        OrderID = orderID;
    }

    public String getsArea() {
        return sArea;
    }

    public void setsArea(String sArea) {
        this.sArea = sArea;
    }

    public String getID() {
        return ID;
    }

    public void setID(String ID) {
        this.ID = ID;
    }
}
