package group.dny.api.component.model;

public class ProductInvalidModel {
    private String message;
    private Integer pID;


    public String getMessage() {
        return message;
    }

    public void setMessage(String message) {
        this.message = message;
    }

    public Integer getpID() {
        return pID;
    }

    public void setpID(Integer pID) {
        this.pID = pID;
    }
}
