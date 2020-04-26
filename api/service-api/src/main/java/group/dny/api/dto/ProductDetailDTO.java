package group.dny.api.dto;

import com.alibaba.fastjson.JSONArray;
import group.dny.api.entity.Product;
import lombok.Data;
import lombok.experimental.Accessors;
import org.springframework.stereotype.Component;

import java.math.BigDecimal;
import java.util.ArrayList;
import java.util.List;

@Component
@Data
@Accessors(chain = true)
public class ProductDetailDTO {
    private Integer lID;
    private String sName;
    private String sRecomm;
    private BigDecimal fPrice;
    private Integer lStock;
    private Integer ShipTemplateID;
    private String sContent;
    private Integer lSale;
    private Integer lSaleShow;
    private Integer SupplierID;
    private List<String> sPic;
    private String sMasterPic;
    private BigDecimal fCostPrice;
    private BigDecimal fFreeShipCost;
    private BigDecimal fShipAdjust;
    private BigDecimal fCostControl;
    private Integer lWeight;
    private BigDecimal fShowPrice;
    private Integer ProductCatID;
    private String PathID;

    private String picUrl;

    public void setMySpic(String spic) {
        List<String> picList = JSONArray.parseArray(spic, String.class);

        List<String> rePicList = new ArrayList<String>();
        for (String pic : picList) {
            if (!pic.contains("http")) {
                rePicList.add(this.picUrl + "/" + pic);
            }
        }

        if (rePicList.size() > 0) {
            this.sPic = rePicList;
        } else {
            this.sPic = picList;
        }

        rePicList = null;
        picList = null;
    }

    public void setsMasterPic(String sMasterPic) {
        if (!sMasterPic.contains("http")) {
            this.sMasterPic = this.picUrl + "/" + sMasterPic;
        } else {
            this.sMasterPic = sMasterPic;
        }
    }

    public ProductDetailDTO getProductDetailView(Product product) {
        this.setFCostControl(product.getFCostControl());
        this.setFCostPrice(product.getFBuyerPrice());
        this.setMySpic(product.getSPic());
        this.setFFreeShipCost(product.getFFreeShipCost());
        this.setFShipAdjust(product.getFShipAdjust());
        this.setLID(product.getLID());
        this.setSName(product.getSName());
        this.setFPrice(product.getFPrice());
        this.setLSale(product.getLSale());
        this.setLSaleShow(product.getLSaleBase());
        this.setLStock(product.getLStock());
        this.setLWeight(product.getLWeight());
        this.setShipTemplateID(product.getShipTemplateID());
        this.setSContent(product.getSContent());
        this.setSRecomm(product.getSRecomm());
        this.setSupplierID(product.getSupplierID());
        this.setsMasterPic(product.getSMasterPic());
        this.setFShowPrice(product.getFShowPrice());
        this.setProductCatID(product.getProductCatID());
        this.setPathID(product.getPathID());

        return this;
    }

}
