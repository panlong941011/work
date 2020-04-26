package group.dny.api.dto;

import java.math.BigDecimal;

public class ProductDTO {
    private Integer lID;
    private String sName;
    private BigDecimal fPrice;
    private Integer lStock;
    private BigDecimal fShowPrice;
    private String sMasterPic;
    private Integer lSale;
    private String sPic;
    private Integer ProductCatID;
    private String PathID;

    public Integer getlID() {
        return lID;
    }

    public void setlID(Integer lID) {
        this.lID = lID;
    }

    public String getsName() {
        return sName;
    }

    public void setsName(String sName) {
        this.sName = sName;
    }

    public BigDecimal getfPrice() {
        return fPrice;
    }

    public void setfPrice(BigDecimal fPrice) {
        this.fPrice = fPrice;
    }

    public Integer getlStock() {
        return lStock;
    }

    public void setlStock(Integer lStock) {
        this.lStock = lStock;
    }

    public BigDecimal getfShowPrice() {
        return fShowPrice;
    }

    public void setfShowPrice(BigDecimal fShowPrice) {
        this.fShowPrice = fShowPrice;
    }

    public String getsMasterPic() {
        return sMasterPic;
    }

    public void setsMasterPic(String sMasterPic) {
        this.sMasterPic = sMasterPic;
    }

    public Integer getlSale() {
        return lSale;
    }

    public void setlSale(Integer lSale) {
        this.lSale = lSale;
    }

    public String getsPic() {
        return sPic;
    }

    public void setsPic(String sPic) {
        this.sPic = sPic;
    }

    public Integer getProductCatID() {
        return ProductCatID;
    }

    public void setProductCatID(Integer productCatID) {
        ProductCatID = productCatID;
    }

    public String getPathID() {
        return PathID;
    }

    public void setPathID(String pathID) {
        PathID = pathID;
    }
}
