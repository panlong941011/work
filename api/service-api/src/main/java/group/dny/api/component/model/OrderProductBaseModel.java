package group.dny.api.component.model;

public class OrderProductBaseModel {
    private String sName;
    private String sSKU;
    private Integer lQuantity;


    public String getsName() {
        return sName;
    }

    public void setsName(String sName) {
        this.sName = sName;
    }

    public String getsSKU() {
        return sSKU;
    }

    public void setsSKU(String sSKU) {
        this.sSKU = sSKU;
    }

    public Integer getlQuantity() {
        return lQuantity;
    }

    public void setlQuantity(Integer lQuantity) {
        this.lQuantity = lQuantity;
    }
}
