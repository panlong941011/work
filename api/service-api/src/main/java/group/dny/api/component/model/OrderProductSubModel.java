package group.dny.api.component.model;

public class OrderProductSubModel extends OrderProductBaseModel {
    private String sKeyword;
    private Integer ProductID;

    public String getsKeyword() {
        return sKeyword;
    }

    public void setsKeyword(String sKeyword) {
        this.sKeyword = sKeyword;
    }

    public Integer getProductID() {
        return ProductID;
    }

    public void setProductID(Integer productID) {
        ProductID = productID;
    }
}
