package group.dny.api.controller;


import group.dny.api.component.aop.ReqLogAnnotation;
import group.dny.api.component.constant.StatusEnum;
import group.dny.api.configuration.PicConfig;
import group.dny.api.dto.ProductDetailDTO;
import group.dny.api.dto.ResultDTO;
import group.dny.api.entity.Area;
import group.dny.api.entity.Product;
import group.dny.api.entity.ShipTemplate;
import group.dny.api.service.IAreaService;
import group.dny.api.service.IProductService;
import group.dny.api.service.IShipTemplateService;
import group.dny.api.utils.ExceptionUtil;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.http.MediaType;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RequestParam;
import org.springframework.web.bind.annotation.RestController;

import java.math.BigDecimal;
import java.util.HashMap;
import java.util.Map;

/**
 * @Description 商品
 * @ClassName ProductController
 * @Author lizhengfan
 * @Date 2019/5/15 9:15
 * @Version 1.0.0
 **/
@RestController
@RequestMapping(value = "/v1/product")
public class ProductController extends BaseController {

    @Autowired
    private IProductService productService;

    @Autowired
    private IAreaService areaService;

    @Autowired
    private IShipTemplateService shipTemplateService;

    @Autowired
    PicConfig picConfig;

    /**
     * @Description 实时库存和价格
     * @Author lizhengfan
     * @Date 2019/5/15 16:07
     * @Param 商品ID
     * @Return ResultDTO
     **/
    @ReqLogAnnotation
    @RequestMapping(value = "/stockAndPrice", produces = MediaType.APPLICATION_JSON_VALUE)
    public ResultDTO stockAndPrice(@RequestParam("pID") Integer pID) {
        try {
            Map<String, Object> map = productService.getStockAndPrice(pID);
            return new ResultDTO(StatusEnum.SUCCESS, map);
        } catch (ExceptionUtil e) {
            return new ResultDTO(e.getValue(), e.getMap());
        }
    }

    /**
     * @Description 分类列表
     * @Author lizhengfan
     * @Date 2019/5/15 16:08
     * @Param
     * @Return ResultDTO
     **/
    @ReqLogAnnotation
    @RequestMapping(value = "/categoryList", produces = MediaType.APPLICATION_JSON_VALUE)
    public ResultDTO categoryList() {
        try {
            Map<String, Object> data = productService.getCategoryList();
            return new ResultDTO(StatusEnum.SUCCESS, data);
        } catch (ExceptionUtil e) {
            return new ResultDTO(e.getValue(), e.getMap());
        }
    }

    /**
     * 分类列表
     *
     * @return
     */

    /**
     * @Description 商品列表
     * @Author lizhengfan
     * @Date 2019/5/15 16:25
     * @Param category 分类ID
     * @Param page 页码
     * @Param pageSize 每页条数
     * @Return ResultDTO
     **/
    @ReqLogAnnotation
    @RequestMapping(value = "/productList", produces = MediaType.APPLICATION_JSON_VALUE)
    public ResultDTO productList(@RequestParam("category") Integer category, @RequestParam(required = false) Integer page, @RequestParam(required = false) Integer pageSize) {
        try {
            Map<String, Object> resultMap = productService.getProductList(page, pageSize, category);
            return new ResultDTO(StatusEnum.SUCCESS, resultMap);
        } catch (ExceptionUtil e) {
            return new ResultDTO(e.getValue(), e.getMap());
        }
    }

    /**
     * 商品详情
     *
     * @return
     */
    @ReqLogAnnotation
    @RequestMapping(value = "/detail", produces = MediaType.APPLICATION_JSON_VALUE)
    public ResultDTO detail(@RequestParam("pID") Integer pID) {
        Product product = null;
        try {
            product = productService.getProductById(pID);

            ProductDetailDTO resultView = new ProductDetailDTO();
            resultView.setPicUrl(picConfig.getUrl());
            resultView.getProductDetailView(product);
            Map<String, Object> map = new HashMap<>();

            map.put("lID", resultView.getLID());
            map.put("sName", resultView.getSName());
            map.put("sPic", resultView.getSPic());

            String sContent = resultView.getSContent();
            sContent = sContent.replaceAll("admin.dny.group", "image.dny.group");
            sContent = sContent.replaceAll("admin.beta.dny.group", "image.dny.group");
            map.put("sContent", sContent);
            map.put("sRecomm", resultView.getSRecomm());
            map.put("fPrice", resultView.getFPrice());
            map.put("lSale", resultView.getLSale());
            map.put("lSaleShow", resultView.getLSaleShow());
            map.put("SupplierID", resultView.getSupplierID());
            map.put("sMasterPic", resultView.getSMasterPic());
            map.put("fCostPrice", resultView.getFCostPrice());
//            map.put("fFreeShipCost", resultView.getFFreeShipCost());
            map.put("fShipAdjust", resultView.getFShipAdjust());
            map.put("fCostControl", resultView.getFCostControl());
            map.put("lStock", resultView.getLStock());
            map.put("ShipTemplateID", resultView.getShipTemplateID());
            map.put("lWeight", resultView.getLWeight());
            map.put("fShowPrice", resultView.getFShowPrice());
            map.put("ProductCatID", resultView.getProductCatID());
            map.put("PathID", resultView.getPathID());

            return new ResultDTO(StatusEnum.SUCCESS, map);
        } catch (ExceptionUtil e) {
            return new ResultDTO(e.getValue());
        }
    }

    /**
     * 商品运费
     *
     * @return
     */
    @ReqLogAnnotation
    @RequestMapping(value = "/ship", produces = MediaType.APPLICATION_JSON_VALUE)
    public ResultDTO ship(@RequestParam("num") Integer num,
                          @RequestParam("ProvinceName") String ProvinceName,
                          @RequestParam("CityName") String cityName,
                          @RequestParam("pID") Integer pID
    ) {

        Product product = null;
        try {
            product = productService.getProductById(pID);
        } catch (ExceptionUtil e) {
            return new ResultDTO(StatusEnum.PRODUCT_NOT_EXIST);
        }

        if (num == null || num.intValue() <= 0) {
            return new ResultDTO(StatusEnum.PRODUCT_QUANTITY_NOT_ZERO);
        }

        //城市不能为空
        if (cityName == null || cityName.length() <= 0) {
            return new ResultDTO(StatusEnum.CITY_NOT_EXIST);
        }

        BigDecimal fTotalMoney = product.getFPrice();
        Integer shipTemplateID = product.getMemberShipTemplateID();

        //商品价格必须不能为0
        if (fTotalMoney == null || fTotalMoney.floatValue() <= 0) {
            return new ResultDTO(StatusEnum.PRODUCT_PRICE_NOT_ZERO);
        }

        //模板ID不能为空
        if (shipTemplateID == null || shipTemplateID <= 0) {
            return new ResultDTO(StatusEnum.SHIP_TEMPLATE_NOT_EXIST);
        }

        //根据模板ID获取模板信息
        ShipTemplate shipTemplate = shipTemplateService.selectById(shipTemplateID);

        if (shipTemplate == null) {
            return new ResultDTO(StatusEnum.SHIP_TEMPLATE_NOT_EXIST);
        }

        Area city = null;
        Area province = null;

        province = areaService.getAreaByProvince(ProvinceName);

        if (province == null) {
            return new ResultDTO(StatusEnum.PROVINCE_NOT_EXIST);
        }

        Area areaParam = new Area();
        areaParam.setUpID(province.getId());
        areaParam.setSName(cityName);
        city = areaService.getAreaByCity(areaParam);

        if (city == null) {
            return new ResultDTO(StatusEnum.CITY_NOT_EXIST);
        }

        try {
            BigDecimal ship = productService.getShip(num, ProvinceName, cityName, shipTemplateID, fTotalMoney, product.getLWeight());
            Map<String, Object> map = new HashMap<>();
            map.put("fShip", ship);

            return new ResultDTO(StatusEnum.SUCCESS, map);
        } catch (ExceptionUtil e) {
            return new ResultDTO(StatusEnum.SUCCESS, e.getValue());
        }
    }
}
