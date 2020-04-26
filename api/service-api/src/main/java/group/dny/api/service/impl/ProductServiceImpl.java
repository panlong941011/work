package group.dny.api.service.impl;

import com.alibaba.fastjson.JSONArray;
import com.alibaba.fastjson.JSONObject;
import com.baomidou.mybatisplus.core.conditions.query.QueryWrapper;
import com.baomidou.mybatisplus.core.metadata.IPage;
import com.baomidou.mybatisplus.extension.plugins.pagination.Page;
import com.baomidou.mybatisplus.extension.service.impl.ServiceImpl;
import group.dny.api.component.constant.StatusEnum;
import group.dny.api.configuration.PicConfig;
import group.dny.api.dto.CateoryDTO;
import group.dny.api.dto.ProductDTO;
import group.dny.api.entity.*;
import group.dny.api.mapper.ProductMapper;
import group.dny.api.service.*;
import group.dny.api.utils.ArithUtil;
import group.dny.api.utils.ExceptionUtil;
import group.dny.api.utils.NumberUtil;
import group.dny.api.utils.ShipUtil;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;

import java.math.BigDecimal;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

/**
 * <p>
 * 服务实现类
 * </p>
 *
 * @author Mars
 * @since 2019-03-14
 */
@Service
public class ProductServiceImpl extends ServiceImpl<ProductMapper, Product> implements IProductService {
    public static final String UNIT_NUMBER_KEY = "Number";
    public static final String UNIT_WEIGHT_KEY = "Weight";
    public static final String UNIT_VOLUME_KEY = "Volume";

    public static final String UNIT_NUMBER_VALUE = "件";
    public static final String UNIT_WEIGHT_VALUE = "m³";
    public static final String UNIT_VOLUME_VALUE = "KG";

    @Autowired
    private ProductMapper productMapper;

    @Autowired
    private IAreaService areaService;

    @Autowired
    private IShipTemplateService shipTemplateService;

    @Autowired
    private IShipTemplateDetailService shipTemplateDetailService;

    @Autowired
    private IShipTemplateFreeService shipTemplateFreeService;

    @Autowired
    private IShipTemplateNoDeliveryService shipTemplateNoDeliveryService;

    @Autowired
    private ISecKillProductService secKillProductService;

    @Autowired
    private IProductSKUService productSKUService;

    @Autowired
    private IProductCatService productCatService;

    @Autowired
    PicConfig picConfig;

    @Override
    public Product getProductById(Integer pID) throws ExceptionUtil {
        Product product = productMapper.getProductById(pID);
        if (product == null) {
            throw new ExceptionUtil(StatusEnum.PRODUCT_NOT_EXIST);
        }
        return product;
    }

    @Override
    public Map<String, Object> getCategoryList() throws ExceptionUtil {
        Map<String, Object> map = new HashMap<>();
        List<ProductCat> productCatList = productCatService.list();
        List<CateoryDTO> dList = new ArrayList<>();
        CateoryDTO dto = null;
        for (ProductCat productCat : productCatList) {
            dto = new CateoryDTO();
            dto.setGradeID(productCat.getGradeID());
            dto.setIProductNum(productCat.getLProductNum());
            dto.setlID(productCat.getLID());
            dto.setsName(productCat.getSName());
            dto.setUpID(productCat.getUpID());

            String sPath = "";
            if (productCat.getSPicPath().length() > 0) {
                sPath = this.picConfig.getUrl() + "/" + productCat.getSPicPath();
            }
            dto.setsPic(sPath);
            dList.add(dto);
        }
        map.put("list", dList);
        return map;
    }

    @Override
    public Map<String, Object> getProductList(Integer page, Integer pageSize, Integer catID) {
        if (page == null) {
            page = 1;
        }

        if (pageSize == null) {
            pageSize = 20;
        }

        Page<Product> pageProduct = new Page<>(page, pageSize);
        QueryWrapper<Product> productQueryWrapper = new QueryWrapper<>();
        if (catID != null && catID > 0)
            productQueryWrapper.eq("ProductCatID", catID);
        productQueryWrapper.eq("bDel", 0);
        productQueryWrapper.eq("bSale", 1);

        IPage<Product> pList = productMapper.getProductList(pageProduct, productQueryWrapper);
        List<Product> list = pList.getRecords();

        List<ProductDTO> resultList = new ArrayList<>();
        ProductDTO productDTO = null;
        for (Product product : list) {
            productDTO = new ProductDTO();
            productDTO.setfPrice(product.getFPrice());
            productDTO.setfShowPrice(product.getFShowPrice());
            productDTO.setlID(product.getLID());
            productDTO.setlSale(product.getLSale());
            productDTO.setlStock(product.getLStock());
            productDTO.setsName(product.getSName());
            productDTO.setsMasterPic(this.picConfig.getUrl() + "/" + product.getSMasterPic());

            String spicStr = product.getSPic();
            JSONArray picArr = JSONArray.parseArray(spicStr);

            List<String> picList = new ArrayList<>();
            for (Object object : picArr) {
                String picStr = this.picConfig.getUrl() + "/" + (String) object;
                picList.add(picStr);
            }

            productDTO.setsPic(JSONObject.toJSONString(picList));
            productDTO.setProductCatID(product.getProductCatID());
            productDTO.setPathID(product.getPathID());

            resultList.add(productDTO);
        }

        Map<String, Object> resultMap = new HashMap<>();
        resultMap.put("totol", pList.getTotal());
        resultMap.put("size", pList.getSize());
        resultMap.put("current", pList.getCurrent());
        resultMap.put("pages", pList.getPages());
        resultMap.put("records", resultList);

        return resultMap;
    }

    @Override
    public Map<String, Object> getStockAndPrice(Integer pID) throws ExceptionUtil {
        Product product = productMapper.getStockAndPrice(pID);

        if (product == null) {
            throw new ExceptionUtil(StatusEnum.PRODUCT_NOT_EXIST);
        } else {
            Map<String, Object> map = new HashMap<>();
            map.put("sale", product.getBSale() && !product.getBDel());//是否在售，如果是被删除了，也表示不可售
            map.put("stockTotal", product.getLStock());//商品库存
            map.put("salePrice", product.getFPrice());//商品售价
            map.put("marketPrice", product.getFShowPrice());//市场价
            map.put("costPrice", product.getFBuyerPrice());//成本价
            map.put("shipAdjust", product.getFShipAdjust());//运费调节
            map.put("costControl", product.getFCostControl());//成本控制

            return map;
        }
    }

    @Override
    public List<Map<String, Object>> getProductChange() {
        return null;
    }

    @Override
    public StatusEnum bInvalidStatus(Integer pID, String sku, Integer num, String provinceName, String cityName, Boolean isSupplier) {
        Product product = this.getProductById(pID);
        SecKillProduct secKillProduct = secKillProductService.getSecKillByPID(pID);
        Boolean haveSku = false;
        if (sku != null && sku.length() > 0) {
            haveSku = productSKUService.haveSku(pID, sku);
        }
        List skuList = productSKUService.getSkuArr(pID);
        Integer shipTemplateID = null;
        if (isSupplier) {
            shipTemplateID = product.getShipTemplateID();
        } else {
            shipTemplateID = product.getMemberShipTemplateID();
        }
        List<ShipTemplateNoDelivery> shipTemplateNoDeliveryList = shipTemplateNoDeliveryService.getShipTemplateNoDeliveryByTemplateID(shipTemplateID);

        Area province = areaService.getAreaByProvince(provinceName);

        Area areaParam = new Area();
        areaParam.setUpID(province.getId());
        areaParam.setSName(cityName);
        Area city = areaService.getAreaByCity(areaParam);

        StatusEnum productStatus = null;

        int haveNoDeliver = haveNoDeliver(shipTemplateNoDeliveryList, city);

        if (!product.getBSale()) {
            productStatus = StatusEnum.PRODUCT_OFFSALE;
        } else if (product.getBDel()) {
            productStatus = StatusEnum.PRODUCT_DEL;
        } else if (secKillProduct == null && product.getbSaleOut()) {
            productStatus = StatusEnum.PRODUCT_SALEOUT;
        } else if (secKillProduct != null && secKillProduct.getBSaleOut()) {
            productStatus = StatusEnum.SECKILL_SALEOUT;
        } else if (sku != null && product.getLStock() < num) {
            productStatus = StatusEnum.PRODUCT_SPEC_LOW_STOCK;
        } else if (secKillProduct != null && sku != null && product.getLStock() < num) {
            productStatus = StatusEnum.PRODUCT_SPEC_LOW_STOCK;
        } else if (secKillProduct != null && sku == null && product.getLStock() < num) {
            productStatus = StatusEnum.PRODUCT_LOW_STOCK;
        } else if (secKillProduct == null && product.getLStock() < num) {
            productStatus = StatusEnum.PRODUCT_LOW_STOCK;
        } else if (sku != null && sku.length() > 0 && !haveSku) {
            productStatus = StatusEnum.PRODUCT_SPEC_NOEXISTS;
        } else if (haveSku && skuList != null) {
            productStatus = StatusEnum.PRODUCT_SPEC_NOSELECTED;
        } else if (shipTemplateNoDeliveryList != null && haveNoDeliver > -1) {
            productStatus = StatusEnum.AREA_NOI_SEND_PRODUCT;
        }

        return productStatus;
    }

    @Override
    public BigDecimal getShip(Integer num, String ProvinceName, String cityName, Integer shipTemplateID, BigDecimal fTotalMoney, Integer weight) throws ExceptionUtil {
        if (num == null || num.intValue() <= 0) {
            throw new ExceptionUtil(StatusEnum.PRODUCT_QUANTITY_NOT_ZERO);
        }

        //城市不能为空
        if (cityName == null || cityName.length() <= 0) {
            throw new ExceptionUtil(StatusEnum.CITY_NOT_EXIST);
        }

        //商品价格必须不能为0
        if (fTotalMoney == null || fTotalMoney.floatValue() <= 0) {
            throw new ExceptionUtil(StatusEnum.PRODUCT_PRICE_NOT_ZERO);
        }

        //模板ID不能为空
        if (shipTemplateID == null || shipTemplateID <= 0) {
            throw new ExceptionUtil(StatusEnum.SHIP_TEMPLATE_NOT_EXIST);
        }

        //根据模板ID获取模板信息
        ShipTemplate shipTemplate = shipTemplateService.selectById(shipTemplateID);

        if (shipTemplate == null) {
            throw new ExceptionUtil(StatusEnum.SHIP_TEMPLATE_NOT_EXIST);
        }

        Area city = null;
        Area province = null;

        province = areaService.getAreaByProvince(ProvinceName);

        if (province == null) {
            throw new ExceptionUtil(StatusEnum.PROVINCE_NOT_EXIST);
        }

        Area areaParam = new Area();
        areaParam.setUpID(province.getId());
        areaParam.setSName(cityName);
        city = areaService.getAreaByCity(areaParam);

        if (city == null) {
            throw new ExceptionUtil(StatusEnum.CITY_NOT_EXIST);
        }

        //计量单位
        String sValuation = shipTemplate.getSValuation();

        Float wNum = 0f;
        String unitName = null;
        if (UNIT_NUMBER_KEY.equals(sValuation)) {
            unitName = UNIT_NUMBER_VALUE;
        } else if (UNIT_WEIGHT_KEY.equals(sValuation)) {
            unitName = UNIT_WEIGHT_VALUE;

            wNum = ArithUtil.divTrunc(weight, 1000, 3).floatValue();
        } else if (UNIT_VOLUME_KEY.equals(sValuation)) {
            unitName = UNIT_VOLUME_VALUE;
        } else {
            unitName = UNIT_NUMBER_VALUE;
        }

//        ShipTemplateNoDelivery shipTemplateNoDelivery = new ShipTemplateNoDelivery();
//        shipTemplateNoDelivery.setShipTemplateID(shipTemplateID);
//        shipTemplateNoDelivery.setSAreaID(city.getId());
//
//        ShipTemplateNoDelivery noDeliver = shipTemplateNoDeliveryService.getShipTemplateNoDeliveryByArea(shipTemplateNoDelivery);

        List<ShipTemplateNoDelivery> shipTemplateNoDeliveryList = shipTemplateNoDeliveryService.getShipTemplateNoDeliveryByTemplateID(shipTemplateID);


        int haveNoDeliver = haveNoDeliver(shipTemplateNoDeliveryList, city);

        //此地区不送货
        if (haveNoDeliver > 0) {
            throw new ExceptionUtil(StatusEnum.AREA_NOI_SEND_PRODUCT);
        }

        String ShipMethod = "EXPRESS";

        ShipTemplateDetail shipTemplateDetailParam = new ShipTemplateDetail();
        shipTemplateDetailParam.setShipTemplateID(shipTemplateID);
        shipTemplateDetailParam.setSShipMethod(ShipMethod);
        shipTemplateDetailParam.setSType("designatedArea");
        shipTemplateDetailParam.setSAreaID("," + city.getId() + ",");

        ShipTemplateDetail shipTemplateDetail = shipTemplateDetailService.getShipTemplateDetail(shipTemplateDetailParam);

        Float shipMoney = null;
        if (shipTemplateDetail != null) {
            if (wNum > 0) {
                shipTemplateDetail.setCountBuy(BigDecimal.valueOf(wNum));
            } else {
                shipTemplateDetail.setCountBuy(BigDecimal.valueOf(num));
            }

            shipMoney = ShipUtil.getShipMoney(shipTemplateDetail);
        } else {

            ShipTemplateDetail defaultShipTemplateDetailParam = new ShipTemplateDetail();
            defaultShipTemplateDetailParam.setShipTemplateID(shipTemplateID);
            defaultShipTemplateDetailParam.setSShipMethod(ShipMethod);
            defaultShipTemplateDetailParam.setSType("default");

            ShipTemplateDetail defaultShipTemplateDetail = shipTemplateDetailService.getShipTemplateDetail(defaultShipTemplateDetailParam);

            if (defaultShipTemplateDetail != null) {
                if (wNum > 0) {
                    defaultShipTemplateDetail.setCountBuy(BigDecimal.valueOf(wNum));
                } else {
                    defaultShipTemplateDetail.setCountBuy(BigDecimal.valueOf(num));
                }

                shipMoney = ShipUtil.getShipMoney(defaultShipTemplateDetail);
            } else {
                throw new ExceptionUtil(StatusEnum.DEFAULT_SHIP_NOT_EXIST);
            }
        }

        Integer bsetFree = shipTemplate.getBSetFree();
        if (bsetFree == 1) {
            ShipTemplateFree shipTemplateFreeParam = new ShipTemplateFree();
            shipTemplateFreeParam.setShipTemplateID(shipTemplateID);
            shipTemplateFreeParam.setSFreeShipMethod(ShipMethod);
            shipTemplateFreeParam.setSFreeAreaID(city.getId());

            ShipTemplateFree shipTemplateFree = shipTemplateFreeService.getShipTemplateFree(shipTemplateFreeParam);

            if (shipTemplateFree != null) {
                Float freeNum = 0f;
                if (shipTemplateFree.getFFreeNumber() != null && shipTemplateFree.getFFreeNumber().length() > 0) {
                    try {
                        freeNum = Float.parseFloat(shipTemplateFree.getFFreeNumber());
                    } catch (RuntimeException e) {
                        System.out.println(e);
                    }
                }
                Float shipFreeNumber = freeNum;
                if (shipTemplate.getSValuation().equals("Number")) {
                    String fFreeNumber = shipTemplateFree.getFFreeNumber();
                    if (fFreeNumber != null) {
                        shipFreeNumber = Float.parseFloat(fFreeNumber);
                    }
                }

                Integer freeType = shipTemplateFree.getLFreeType();

                if (freeType == 0) {
                    if (shipTemplate.getSValuation().equals("Number")) {
                        if (num >= shipFreeNumber) {
                            shipMoney = 0f;
                        }
                    } else {
                        if (wNum <= shipFreeNumber) {
                            shipMoney = 0f;
                        }
                    }
                } else if (freeType == 1) {
                    if (fTotalMoney.floatValue() >= Float.parseFloat(shipTemplateFree.getFFreeMoney())) {
                        shipMoney = 0f;
                    }
                } else {
                    if (num >= shipFreeNumber && fTotalMoney.floatValue() >= Float.parseFloat(shipTemplateFree.getFFreeMoney())) {
                        shipMoney = 0f;
                    }
                }
            }
        }

        String sShipMoney = NumberUtil.formatNumber(shipMoney, 2);

        return new BigDecimal(sShipMoney);
    }

    @Override
    public void updateStockByID(Product product) {
        productMapper.updateStockByID(product);
    }


    /**
     * @Description 判断此城市是否是不可发货区域
     * @Author lizhengfan
     * @Date 2019/5/22 9:04
     * @Param shipTemplateNoDeliveryList 模板数据
     * @Param city 城市数据
     * @Return int
     **/
    public int haveNoDeliver(List<ShipTemplateNoDelivery> shipTemplateNoDeliveryList, Area city) {
        Integer haveNoDeliver = -1;
        if (shipTemplateNoDeliveryList != null) {
            for (ShipTemplateNoDelivery shipTemplateNoDelivery : shipTemplateNoDeliveryList) {
                haveNoDeliver = shipTemplateNoDelivery.getSAreaID().indexOf(city.getId());
                if (haveNoDeliver > 0) {
                    break;
                }
            }
        }

        return haveNoDeliver;
    }


}
