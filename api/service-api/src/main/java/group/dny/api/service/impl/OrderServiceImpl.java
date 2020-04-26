package group.dny.api.service.impl;

import com.alibaba.fastjson.JSONArray;
import com.baomidou.mybatisplus.extension.service.impl.ServiceImpl;
import group.dny.api.component.constant.*;
import group.dny.api.component.model.*;
import group.dny.api.entity.*;
import group.dny.api.mapper.OrderMapper;
import group.dny.api.service.*;
import group.dny.api.utils.*;
import org.apache.commons.lang.StringUtils;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;
import org.springframework.transaction.annotation.Transactional;

import java.math.BigDecimal;
import java.util.*;

/**
 * <p>
 * 服务实现类
 * </p>
 *
 * @author lizhengfan
 * @since 2019-03-21
 */
@Service
public class OrderServiceImpl extends ServiceImpl<OrderMapper, Order> implements IOrderService {
    private static Logger logger = LoggerFactory.getLogger(OrderServiceImpl.class);
    @Autowired
    private IProductService productService;

    @Autowired
    private IShipTemplateService shipTemplateService;

    @Autowired
    private IProductSKUService productSKUService;

    @Autowired
    private IPreOrderService preOrderService;

    @Autowired
    private IProductStockChangeService productStockChangeService;

    @Autowired
    private OrderMapper orderMapper;

    @Autowired
    private IOrderDetailService orderDetailService;

    @Autowired
    private IOrderAddressService orderAddressService;

    @Autowired
    private IBuyerService buyerService;

    @Autowired
    private IDealFlowService dealFlowService;

    @Autowired
    private ISupplierService supplierService;

    @Autowired
    private IAreaService areaService;

    @Autowired
    private IOrderLogisticsService orderLogisticsService;

    @Autowired
    private IExpressCompanyService expressCompanyService;

    @Autowired
    private IAccessTokenService accessTokenService;

    @Override
    public Map<String, Object> submit(OrderModel model) throws ExceptionUtil {
        Map<String, Object> map = new HashMap<>();
        synchronized (this) {
            List<ProductSkuModel> orderList = null;
            String order = model.getOrder();
            String province = model.getProvince();
            String city = model.getCity();
            String area = model.getArea();
            String address = model.getAddress();
            String name = model.getName();
            String phone = model.getPhone();
            String sn = model.getSn();
            String message = model.getMessage();
            String token = model.getToken();
            Integer buyerID = model.getBuyerID();
            Integer id = model.getId();

            orderList = JSONArray.parseArray(order, ProductSkuModel.class);

            StatusEnum userStatus = ValidateUtil.userInfoValidate(province, city, area, address, name, phone);

            if (userStatus != StatusEnum.SUCCESS) {
                throw new ExceptionUtil(userStatus);
            }

            List<ProductInvalidModel> validList = this.bInvalidStatus(orderList, province, city, false);

            if (validList != null && validList.size() > 0) {
                map.put("invalid", validList);
                throw new ExceptionUtil(map, StatusEnum.FAILURE);
            }

            Date nowTime = new Date();
            String orderSn = null;
            if (sn != null && sn.length() > 0) {
                orderSn = sn;
            } else {
                orderSn = CommonUtil.createOrderSn();
            }

            PreOrder preOrder = new PreOrder();
            preOrder.setDNewDate(nowTime);
            preOrder.setSAddress(address);
            preOrder.setSProvince(province);
            preOrder.setSCity(city);
            preOrder.setSArea(area);
            preOrder.setSReceiverName(name);
            preOrder.setSMobile(phone);
            preOrder.setSName(orderSn);
            preOrder.setSMessage(message);
            preOrder.setBuyerID(buyerID);
            preOrder.setWholesalerID(id);

            int isInsert = preOrderService.insert(preOrder);

            if (buyerID == null) {
                AccessToken accessToken = accessTokenService.getAccessTokenByToken(token);
                buyerID = accessToken.getBuyerID();
            }

            try {
                productStockChangeService.insertProductStockChangeRecord(orderList, orderSn, buyerID);
            } catch (ExceptionUtil e) {
                throw e;
            }

            //格式化订单数据
            List<OrderDataModel> orderParams = new ArrayList<>();
            if (orderList != null && orderList.size() > 0) {
                OrderDataModel orderDataModel = null;
                for (ProductSkuModel productSkuModel : orderList) {
                    orderDataModel = new OrderDataModel();
                    orderDataModel.setSku(productSkuModel.getSku());
                    orderDataModel.setPID(productSkuModel.getPID());
                    orderDataModel.setNum(productSkuModel.getNum());
                    orderDataModel.setBuyerID(buyerID);

                    orderParams.add(orderDataModel);
                }
            } else {
                throw new ExceptionUtil(StatusEnum.PRODUCT_NOT_EXIST);
            }

            //客户运费
            List<OrderTemplateModel> buyerList = this.getOrderData(orderParams, false);
            BigDecimal fMemberShip = this.getOrderShip(buyerList, province, city);

            //供应商运费
            List<OrderTemplateModel> supplierList = this.getOrderData(orderParams, true);
            BigDecimal fSupplierShip = this.getOrderShip(supplierList, province, city);

            BigDecimal orderFTotal = BigDecimal.valueOf(0);
            for (OrderTemplateModel orderTemplateModel : supplierList) {
                //采购价
                BigDecimal buyerTotal = orderTemplateModel.getFBuyerTotal();
                //进货价
                BigDecimal costTotal = orderTemplateModel.getFCostTotal();

                if (buyerID != null) {
                    Supplier supplier = supplierService.getSupplierByID(orderTemplateModel.getSupplierID());
                    Integer supplierBuyerIDbuyerID = supplier.getBuyerID();
                    if (supplierBuyerIDbuyerID != null && buyerID == supplierBuyerIDbuyerID) {
                        buyerTotal = costTotal;
                    }
                }

                orderFTotal = ArithUtil.addRounding(orderFTotal, buyerTotal, 2);
            }

            orderFTotal = ArithUtil.addRounding(orderFTotal, fSupplierShip, 2);


            //更新preorder
            preOrder.setFShip(fSupplierShip);
            preOrder.setFTotal(orderFTotal);
            preOrderService.updateOrderTotal(preOrder);

            map.put("fMemberShip", fMemberShip);
            map.put("fSupplierShip", fSupplierShip);
            map.put("sn", orderSn);
        }

        return map;
    }

    @Override
    public Map<String, Object> ship(String sku, String cityName, String provinceName, Boolean isSupplier) throws ExceptionUtil {
        Map<String, Object> map = new HashMap<>();
        List<ProductSkuModel> paramsList = JSONArray.parseArray(sku, ProductSkuModel.class);

        if (paramsList == null) {
            throw new ExceptionUtil(StatusEnum.SKU_NOT_EMPTY);
        }

        List<ProductInvalidModel> validList = this.bInvalidStatus(paramsList, provinceName, cityName, isSupplier);
        if (validList != null && validList.size() > 0) {
            map.put("invalid", validList);
            throw new ExceptionUtil(map, StatusEnum.FAILURE);
        }

        //格式化订单数据
        List<OrderDataModel> orderParams = new ArrayList<>();
        if (paramsList != null && paramsList.size() > 0) {
            OrderDataModel orderDataModel = null;
            for (ProductSkuModel productSkuModel : paramsList) {
                orderDataModel = new OrderDataModel();
                orderDataModel.setSku(productSkuModel.getSku());
                orderDataModel.setPID(productSkuModel.getPID());
                orderDataModel.setNum(productSkuModel.getNum());

                orderParams.add(orderDataModel);
            }
        } else {
            throw new ExceptionUtil(StatusEnum.PRODUCT_NOT_EXIST);
        }

        List<OrderTemplateModel> dataList = this.getOrderData(orderParams, isSupplier);

        BigDecimal totalShip = null;
        try {
            totalShip = this.getOrderShip(dataList, provinceName, cityName);
        } catch (ExceptionUtil e) {
            throw e;
        }

        map.put("fShip", totalShip);

        return map;
    }

    @Override
    public List<OrderStatusModel> status(String sn) throws ExceptionUtil {
        if (sn == null || sn.length() == 0) {
            throw new ExceptionUtil(StatusEnum.ORDER_SN_NOT_EMPTY);
        }
        List<OrderStatusModel> orderStatusList = null;
        List<Order> orderList = this.getOrderBySn(sn);
        List<OrderDetail> orderDetailList = orderDetailService.getOrderStatuByClientSn(sn);
        if (orderList == null || orderList.size() == 0) {
            throw new ExceptionUtil(StatusEnum.ORDER_NOT_EXIST);
        } else {

            try {
                orderStatusList = this.getOrderStatusListByOrder(orderList);
            } catch (ExceptionUtil e) {
                throw e;
            }

            List<OrderStatusModel> resultList = new ArrayList<>();
            OrderStatusModel orderResultStatusModel = null;
            for (OrderDetail orderDetail : orderDetailList) {
                orderResultStatusModel = new OrderStatusModel();
                orderResultStatusModel.setpID(orderDetail.getProductID());
                String refundStatus = orderDetail.getStatusID();
                String refundStatusName = "无";
                if (refundStatus != null) {
                    refundStatusName = RefundStatusEnum.getEnumByKey(refundStatus).getValue();
                }

                orderResultStatusModel.setRefundStatus(refundStatusName);
                Order order = this.getOrderByID(orderDetail.getOrderID());
                String orderStatus = order.getStatusID();
                OrderStatusEnum orderStatusEnum = OrderStatusEnum.getEnumByKey(orderStatus);
                if (orderStatusEnum == null) {
                    throw new ExceptionUtil(StatusEnum.ORDER_STATUS_NOT_EXIST);
                }
                String orderStatusName = orderStatusEnum.getValue();
                orderResultStatusModel.setOrderStatus(orderStatusName);

                for (OrderStatusModel orderStatusModel : orderStatusList) {
                    int modelPID = orderStatusModel.getpID();
                    int detailPID = orderDetail.getProductID();
                    if (modelPID == detailPID) {
                        orderResultStatusModel.setExpress(orderStatusModel.getExpress());
                        break;
                    }
                }

                resultList.add(orderResultStatusModel);
            }
            return resultList;
        }
    }

    public BigDecimal getOrderShip(List<OrderTemplateModel> dataList, String provinceName, String cityName) throws ExceptionUtil {
        BigDecimal totolShip = new BigDecimal(0);

        for (int i = 0; i < dataList.size(); i++) {
            Integer num = dataList.get(i).getNum();
            Integer weight = dataList.get(i).getWeight();
            Integer shipTemplateID = dataList.get(i).getShipTemplateID();
            BigDecimal fTotal = dataList.get(i).getFTotal();

            try {
                BigDecimal productPrice = productService.getShip(num, provinceName, cityName, shipTemplateID, fTotal, weight);
                totolShip = ArithUtil.addRounding(totolShip, productPrice, 2);
            } catch (ExceptionUtil e) {
                throw e;
            }
        }

        return totolShip;
    }

    /**
     * @param list
     * @param isSupplier 客户需付的运费为false,
     * @return
     */
    @Override
    public List<OrderTemplateModel> getOrderData(List<OrderDataModel> list, Boolean isSupplier) {
        List<OrderTemplateModel> resultList = new ArrayList<OrderTemplateModel>();

        OrderTemplateModel orderTemplateModel = null;
        for (OrderDataModel view : list) {
            Integer pID = view.getPID();
            Integer num = view.getNum();
            String sku = view.getSku();

            Product product = productService.getProductById(pID);
            Integer supplierID = product.getSupplierID();
            Integer shipTemplateID = null;

            if (!isSupplier) {
                shipTemplateID = product.getMemberShipTemplateID();
            } else {
                shipTemplateID = product.getShipTemplateID();
            }

            ShipTemplate shipTemplate = shipTemplateService.selectById(shipTemplateID);
            String cityID = shipTemplate.getCityID();

//            String supplierStr = supplierID + "_" + cityID + "_" + shipTemplateID;
            String supplierStr = supplierID + "_" + cityID;

            BigDecimal fPrice = null;
            BigDecimal fBuyerPrice = null;
            BigDecimal fCostPrice = null;
            Integer fWeight = null;

            ProductSKU skuParam = new ProductSKU();
            skuParam.setProductID(pID);
            skuParam.setSValue(sku);
            ProductSKU skuInfo = productSKUService.getProducetSkuByPIDAndSku(skuParam);

            if (skuInfo != null) {
                fPrice = ArithUtil.mulRounding(skuInfo.getFPrice(), num, 2);
                fBuyerPrice = ArithUtil.mulRounding(skuInfo.getFBuyerPrice(), num, 2);
                fCostPrice = ArithUtil.mulRounding(skuInfo.getFCostPrice(), num, 2);
            } else {
                fPrice = ArithUtil.mulRounding(product.getFPrice(), num, 2);
                fBuyerPrice = ArithUtil.mulRounding(product.getFBuyerPrice(), num, 2);
                fCostPrice = ArithUtil.mulRounding(product.getFSupplierPrice(), num, 2);
            }

            Integer buyerID = view.getBuyerID();
            if (buyerID != null) {
                Supplier supplier = supplierService.getSupplierByID(supplierID);
                Integer supplierBuyerIDbuyerID = supplier.getBuyerID();
                if (supplierBuyerIDbuyerID != null && buyerID == supplierBuyerIDbuyerID) {
                    fBuyerPrice = fCostPrice;
                }
            }

            Integer pWeight = product.getLWeight();
            if (pWeight == null) pWeight = 0;
            fWeight = ArithUtil.mulRounding(pWeight, num, 2).intValue();

            OrderTemplateModel templateModel = haveSupplierTemplate(resultList, supplierStr);
            //存在则把数量,价格，重量加在一起
            if (templateModel != null) {
                //获取原来的信息
                Integer haveNum = templateModel.getNum();
                BigDecimal havePrice = templateModel.getFTotal();
                Integer haveWeight = templateModel.getWeight();

                BigDecimal haveBuyerPrice = templateModel.getFBuyerTotal();
                BigDecimal haveCostPrice = templateModel.getFCostTotal();

                Integer addAfterNum = haveNum + num;
                BigDecimal addAfterPrice = ArithUtil.addRounding(havePrice, fPrice, 2);
                Integer addAfterWeight = haveWeight + fWeight;

                templateModel.setNum(addAfterNum);
                templateModel.setFTotal(addAfterPrice);
                templateModel.setWeight(addAfterWeight);
                BigDecimal addAfterBuyerPrice = ArithUtil.addRounding(haveBuyerPrice, fBuyerPrice, 2);
                BigDecimal addAfterCostPrice = ArithUtil.addRounding(haveCostPrice, fCostPrice, 2);
                templateModel.setFBuyerTotal(addAfterBuyerPrice);
                templateModel.setFCostTotal(addAfterCostPrice);

                templateModel.getProductList().add(view);
            } else {
                orderTemplateModel = new OrderTemplateModel();
                orderTemplateModel.setShipTemplateID(shipTemplateID);
                orderTemplateModel.setSupplierID(supplierID);

                orderTemplateModel.setFTotal(fPrice);

                orderTemplateModel.setWeight(fWeight);
                orderTemplateModel.setNum(num);
                orderTemplateModel.setCityID(cityID);
                orderTemplateModel.setSupplierStr(supplierStr);
                orderTemplateModel.setFBuyerTotal(fBuyerPrice);
                orderTemplateModel.setFCostTotal(fCostPrice);
                orderTemplateModel.setSName(view.getSName());
                orderTemplateModel.setBuyerID(view.getBuyerID());
                orderTemplateModel.setOrderShipTemplateID(product.getShipTemplateID());

                List<ProductSkuModel> productList = new ArrayList<ProductSkuModel>();
                productList.add(view);
                orderTemplateModel.setProductList(productList);


                resultList.add(orderTemplateModel);
            }
        }

        return resultList;
    }

    private OrderTemplateModel haveSupplierTemplate(List<OrderTemplateModel> list, String supplierStr) {
        for (OrderTemplateModel model : list) {
            if (model.getSupplierStr().equals(supplierStr)) {
                return model;
            }
        }
        return null;
    }

    @Override
    public List<ProductInvalidModel> bInvalidStatus(List<ProductSkuModel> list, String provinceName, String cityName, Boolean isSupplier) {
        List<ProductInvalidModel> validList = new ArrayList<ProductInvalidModel>();
        ProductInvalidModel validView = null;
        if (list != null && list.size() > 0) {
            for (ProductSkuModel productSkuModel : list) {
                StatusEnum status = productService.bInvalidStatus(productSkuModel.getPID(), productSkuModel.getSku(), productSkuModel.getNum(), provinceName, cityName, isSupplier);
                if (status != null) {
                    validView = new ProductInvalidModel();
                    validView.setpID(productSkuModel.getPID());
                    validView.setMessage(status.getValue());
                    validList.add(validView);
                }
            }
        }
        return validList;
    }

    @Transactional
    @Override
    public void confirmPay(String sn) throws ExceptionUtil {
        synchronized (this) {
            if (sn == null || sn.length() == 0) {
                throw new ExceptionUtil(StatusEnum.ORDER_SN_NOT_EMPTY);
            }

            //先判断SN是否在订单表中是否已经存在，存在则直接返回防止重复订单
            List<Order> orderList = orderMapper.getOrderBySn(sn);
            if (orderList != null && orderList.size() > 0) {
                throw new ExceptionUtil(StatusEnum.ORDER_RECREATE);
            }

            //通过SN获取库存变动记录
            //通过SN获取用户的地址等信息
            List<PreOrder> preOrderList = preOrderService.getPreOrderBySn(sn);
            PreOrder preOrder = null;
            String provinceID = null;
            String cityID = null;
            String areaID = null;

            Integer purchaseID = null;
            Integer wholesalerID = null;
            String typeID = null;
            String closeReason = null;
            if (preOrderList != null && preOrderList.size() > 0) {
                preOrder = preOrderList.get(0);

                String provinceName = preOrder.getSProvince();
                String cityName = preOrder.getSCity();
                String areaName = preOrder.getSArea();
                purchaseID = preOrder.getBuyerID();
                wholesalerID = preOrder.getWholesalerID();
                closeReason = preOrder.getSCloseReason();
                if (purchaseID != null) {
                    typeID = "wholesale";
                }

                Area provineArea = areaService.getAreaByProvince(provinceName);

                if (provineArea != null) {
                    provinceID = provineArea.getId();
                }

                Area cityParam = new Area();
                cityParam.setUpID(provinceID);
                cityParam.setSName(cityName);
                Area cityArea = areaService.getAreaByCity(cityParam);

                if (cityArea != null) {
                    cityID = cityArea.getId();
                }

                Area areaParam = new Area();
                areaParam.setUpID(cityID);
                areaParam.setSName(areaName);
                Area areaInfo = areaService.getAreaByArea(areaParam);

                if (areaInfo != null) {
                    areaID = areaInfo.getId();
                }
            }

            if (preOrder.getBClosed() == NumberEnum.ONE.getNumber() || preOrder.getBClosed() == NumberEnum.MINER.getNumber()) {
                throw new ExceptionUtil(closeReason, StatusEnum.SYS_ERROR);
            }

            List<ProductStockChange> productStockChangeList = productStockChangeService.getProductStockChangeListBySn(sn);

            //先通过变动记录整理出所有拆单所需的基本数据
            //主要是sku,product,num,buyerID,sname这几个数据
            //sName对应商品库存变动表中的sName

            List<OrderDataModel> viewList = new ArrayList<OrderDataModel>();

            OrderDataModel orderDataModel = null;
            for (ProductStockChange productStockChange : productStockChangeList) {
                orderDataModel = new OrderDataModel();
                orderDataModel.setNum(productStockChange.getLChange());
                orderDataModel.setPID(productStockChange.getProductID());
                orderDataModel.setSku(productStockChange.getSSKU());
                orderDataModel.setSName(productStockChange.getSName());
                orderDataModel.setBuyerID(productStockChange.getBuyerID());

                viewList.add(orderDataModel);
            }

            //生成对应供应商拆单数据
            List<OrderTemplateModel> orderTemplateModelList = this.getOrderData(viewList, false);

            Order order = null;
            OrderDetail orderDetail = null;

            Integer buyerID = null;
            try {
                //开始计算需要的数据
                for (OrderTemplateModel orderTemplateModel : orderTemplateModelList) {
                    //获取订单信息
                    //算运费
                    Integer totalNum = orderTemplateModel.getNum();
                    Integer totalWeight = orderTemplateModel.getWeight();
                    BigDecimal totalFPrice = orderTemplateModel.getFTotal();
                    Integer shipTemplateID = orderTemplateModel.getOrderShipTemplateID();

                    BigDecimal fShip = productService.getShip(totalNum, preOrder.getSProvince(), preOrder.getSCity(), shipTemplateID, totalFPrice, totalWeight);
                    order = new Order();

                    String orderName = CommonUtil.createOrderSn();

                    //判断订单是否已经存在
                    String sclientSn = orderTemplateModel.getSName();
                    buyerID = orderTemplateModel.getBuyerID();
                    order.setSName(orderName);
                    order.setSClientSN(sclientSn);
                    order.setSupplierID(orderTemplateModel.getSupplierID());
                    order.setBuyerID(buyerID);
                    order.setStatusID("unpaid");
                    order.setDNewDate(new Date());
                    order.setFShip(fShip);
                    order.setFBuyerProductPaid(orderTemplateModel.getFBuyerTotal());
                    order.setFSupplierProductIncome(orderTemplateModel.getFCostTotal());
                    order.setFBuyerPaid(ArithUtil.addRounding(order.getFBuyerProductPaid(), fShip, 2));
                    order.setFSupplierIncome(ArithUtil.addRounding(order.getFSupplierProductIncome(), fShip, 2));
                    order.setFProfit(ArithUtil.subRounding(order.getFBuyerProductPaid(), order.getFSupplierProductIncome(), 2));
                    order.setSMessage(preOrder.getSMessage());
                    order.setPurchaseID(purchaseID);
                    order.setWholesalerID(wholesalerID);
                    order.setTypeID(typeID);
                    this.insert(order);

                    Integer orderID = order.getLID();

                    //获取订单详情
                    List<ProductSkuModel> productList = orderTemplateModel.getProductList();
                    for (ProductSkuModel orderProductSkuModel : productList) {
                        Integer pID = orderProductSkuModel.getPID();
                        String sku = orderProductSkuModel.getSku();
                        Product productDetail = productService.getProductById(pID);

                        orderDetail = new OrderDetail();
                        ProductSKU skuParam = new ProductSKU();
                        skuParam.setProductID(pID);
                        skuParam.setSValue(sku);
                        ProductSKU skuInfo = productSKUService.getProducetSkuByPIDAndSku(skuParam);

                        BigDecimal fBuyerPrice = null;
                        BigDecimal fCostPrice = null;

                        if (skuInfo != null) {
                            fBuyerPrice = skuInfo.getFBuyerPrice();
                            fCostPrice = skuInfo.getFCostPrice();
                            orderDetail.setSSKU(skuInfo.getSkuName());
                        } else {
                            fBuyerPrice = productDetail.getFBuyerPrice();
                            fCostPrice = productDetail.getFSupplierPrice();
                        }

                        orderDetail.setFBuyerPrice(fBuyerPrice);
                        orderDetail.setFSupplierPrice(fCostPrice);
                        orderDetail.setSName(productDetail.getSName());
                        orderDetail.setBuyerID(order.getBuyerID());
                        orderDetail.setProductID(pID);
                        orderDetail.setOrderID(orderID);
                        orderDetail.setShipTemplateID(orderTemplateModel.getShipTemplateID());
                        orderDetail.setSPic(productDetail.getSMasterPic());
                        orderDetail.setLQuantity(orderProductSkuModel.getNum());
                        orderDetail.setFBuyerSalePrice(productDetail.getFPrice());
                        orderDetail.setFSupplierIncomeTotal(ArithUtil.mulRounding(orderDetail.getFSupplierPrice(), orderDetail.getLQuantity(), 2));

                        Integer detailBuyerID = orderTemplateModel.getBuyerID();

                        Boolean needSet = true;
                        if (detailBuyerID != null) {
                            Supplier supplier = supplierService.getSupplierByID(productDetail.getSupplierID());
                            Integer supplierBuyerIDbuyerID = supplier.getBuyerID();
                            if (supplierBuyerIDbuyerID != null && detailBuyerID == supplierBuyerIDbuyerID) {
                                orderDetail.setFBuyerPaidTotal(orderDetail.getFSupplierIncomeTotal());
                                needSet = false;
                            }
                        }

                        if (needSet) {
                            orderDetail.setFBuyerPaidTotal(ArithUtil.mulRounding(orderDetail.getFBuyerPrice(), orderDetail.getLQuantity(), 2));
                        }
                        orderDetail.setFShip(order.getFShip());
                        orderDetailService.insert(orderDetail);
                    }

                    Buyer buyer = buyerService.findBuyerByID(buyerID);

                    //保存地址
                    OrderAddress orderAddress = new OrderAddress();
                    //用户的名称
                    orderAddress.setSName(preOrder.getSReceiverName());
                    orderAddress.setOrderID(orderID);
                    orderAddress.setProvinceID(provinceID);
                    orderAddress.setCityID(cityID);
                    orderAddress.setAreaID(areaID);
                    orderAddress.setSAddress(preOrder.getSAddress());
                    orderAddress.setSMobile(preOrder.getSMobile());
                    orderAddressService.insert(orderAddress);

                    order.setOrderAddressID(orderAddress.getLID());

                    //更新订单地址信息
                    this.updateOrderAddressByID(order);

                    //更新库存变动记录里的订单号
                    ProductStockChange productStockChange = new ProductStockChange();
                    productStockChange.setSName(orderTemplateModel.getSName());
                    productStockChange.setOrderID(order.getLID());

                    productStockChangeService.updateOrderIDBySn(productStockChange);

                    //扣除采购款
                    if (order.getLID() == null) {
                        throw new ExceptionUtil(StatusEnum.ORDER_CREATE_FAIL);
                    }

                    try {
                        this.orderPayment(order.getLID());
                    } catch (ExceptionUtil e) {
                        throw e;
                    }

                    //统计供应商待结算金额
                    if (order.getSupplierID() == null) {
                        throw new ExceptionUtil(StatusEnum.ORDER_CREATE_FAIL);
                    }
                    supplierService.computeWaitMoney(order.getSupplierID(), null);
                }

                preOrderService.updatePreOrderStatus(preOrder.getSName(), false);
            } catch (ExceptionUtil e) {
                throw e;
            }
        }
    }

    @Override
    public int insert(Order entity) {
        return orderMapper.insertOrder(entity);
    }

    @Override
    public void updateOrderAddressByID(Order order) {
        orderMapper.updateOrderAddressByID(order);
    }

    @Override
    public void orderPayment(Integer orderID) throws ExceptionUtil {
        Order order = this.getOrderByID(orderID);
        if (order == null) {
            throw new ExceptionUtil(StatusEnum.ORDER_NOT_EXIST);
        }
        Integer buyerID = order.getBuyerID();

        Buyer buyer = buyerService.findBuyerByID(buyerID);
        if (buyer == null) {
            throw new ExceptionUtil(StatusEnum.BUYER_NOT_EXIST);
        }

        BigDecimal fBalance = buyer.getFBalance();
        Float have = ArithUtil.subRounding(fBalance, order.getFBuyerPaid(), 2).floatValue();

        logger.error("采购款剩余:" + have);
        if (have >= 0) {
            //更新订单的字符状态
            order.setStatusID("paid");
            this.updateOrderStatusByID(order);

            DealFlowModel dealFlowModel = new DealFlowModel();
            dealFlowModel.setSName("订单" + order.getSName() + "扣款");
            dealFlowModel.setFMoney(order.getFBuyerPaid());
            dealFlowModel.setMemberID(order.getBuyerID());
            dealFlowModel.setRoleType("buyer");
            dealFlowModel.setTypeID(Constant.dealFlowType.get("buy"));
            dealFlowModel.setDealID(orderID);

            try {
                dealFlowService.change(dealFlowModel);
            } catch (ExceptionUtil e) {
                throw e;
            }

        }
    }

    @Override
    public Order getOrderByID(Integer orderID) {
        return orderMapper.getOrderByID(orderID);
    }

    @Override
    public void updateOrderStatusByID(Order order) {
        orderMapper.updateOrderStatusByID(order);
    }

    @Override
    public BigDecimal getWaitMoneyBySupplierID(Integer supplierID) {
        return orderMapper.getWaitMoneyBySupplierID(supplierID);
    }

    @Override
    public List<Order> getOrderBySn(String sn) {
        return orderMapper.getOrderBySn(sn);
    }

    @Transactional
    @Override
    public void confirmReceived(String sn) throws ExceptionUtil {
        //sn不能为空
        if (sn == null || sn.length() == 0) {
            throw new ExceptionUtil(StatusEnum.ORDER_SN_NOT_EMPTY);
        }
        List<Order> orderList = orderMapper.getOrderBySn(sn);

        List<Order> needList = new ArrayList<>();
        for (Order order : orderList) {
            if (order.getStatusID().equals(StatusEnum.ORDER_SUCCESS.getValue())) {
                continue;
            }

            Boolean needAdd = false;
            List<OrderDetail> detailList = order.getDetailList();
            for (OrderDetail detail : detailList) {
                //是否在退款中
                if (!detail.getBRefunding()) {
                    needAdd = false;
                } else {
                    needAdd = true;
                }
            }

            if (!needAdd) {
                continue;
            }

            needList.add(order);
        }

        DealFlowModel dealFlowModel = null;
        for (Order order : needList) {
            order.setDReceiveDate(new Date());
            order.setStatusID(StatusEnum.ORDER_SUCCESS.getValue());
            BigDecimal buyerProductPaid = order.getFBuyerProductPaid();
            BigDecimal ship = order.getFShip();
            BigDecimal buyerRefund = order.getFBuyerRefund();

            BigDecimal supplierProductIncome = order.getFSupplierProductIncome();
            BigDecimal supplierRefund = order.getFSupplierRefund();

            if (buyerRefund == null) buyerRefund = BigDecimal.valueOf(0);
            if (supplierRefund == null) supplierRefund = BigDecimal.valueOf(0);

            BigDecimal buyerPaid = ArithUtil.subRounding(ArithUtil.addRounding(buyerProductPaid, ship, 2), buyerRefund, 2);
            BigDecimal supplierIncome = ArithUtil.subRounding(ArithUtil.addRounding(supplierProductIncome, ship, 2), supplierRefund, 2);

            order.setFBuyerPaid(buyerPaid);
            order.setFSupplierIncome(supplierIncome);
            this.updateOrderReceiveInfo(order);

            dealFlowModel = new DealFlowModel();
            dealFlowModel.setSName("订单号" + order.getSName() + "收入");
            dealFlowModel.setFMoney(supplierIncome);
            dealFlowModel.setOrder(order.getSName());
            dealFlowModel.setMemberID(order.getSupplierID());
            dealFlowModel.setRoleType("supplier");
            dealFlowModel.setTypeID(Constant.dealFlowType.get("income"));
            dealFlowModel.setDealID(order.getLID());

            try {
                dealFlowService.change(dealFlowModel);
            } catch (ExceptionUtil e) {
                throw e;
            }
        }
    }

    @Override
    public void updateOrderReceiveInfo(Order order) {
        orderMapper.updateOrderReceiveInfo(order);
    }

    @Transactional
    @Override
    public void modifyAddress(OrderModel model) throws ExceptionUtil {
        String province = model.getProvince();
        String city = model.getCity();
        String area = model.getArea();
        String address = model.getAddress();
        String name = model.getName();
        String phone = model.getPhone();
        String sn = model.getSn();

        //sn不能为空
        if (sn == null || sn.length() == 0) {
            throw new ExceptionUtil(StatusEnum.ORDER_SN_NOT_EMPTY);
        }

        StatusEnum userStatus = ValidateUtil.userInfoValidate(province, city, area, address, name, phone);

        if (userStatus != StatusEnum.SUCCESS) {
            throw new ExceptionUtil(userStatus);
        }

        List<Order> orderList = this.getOrderBySn(sn);
        if (orderList != null && orderList.size() > 0) {
            for (Order order : orderList) {
                String status = order.getStatusID();
                if (!StatusEnum.ORDER_PAID.getValue().equals(status)) {
                    throw new ExceptionUtil(StatusEnum.ORDER_NOT_PAID);
                }
            }
        } else {
            throw new ExceptionUtil(StatusEnum.ORDER_NOT_EXIST);
        }

        Area provineNameArea = null;
        try {
            provineNameArea = areaService.getAreaByID(province);
        } catch (RuntimeException e) {
            System.out.println(e);
        }
        if (provineNameArea == null) {
            throw new ExceptionUtil(StatusEnum.PROVINCE_NOT_EXIST);
        }
        Area cityNameArea = areaService.getAreaByID(city);

        if (cityNameArea == null) {
            throw new ExceptionUtil(StatusEnum.CITY_NOT_EXIST);
        }

        Area areaNameArea = areaService.getAreaByID(area);
        String sAreaName = "";
        if (areaNameArea != null) {
            sAreaName = areaNameArea.getSName();
        }

        PreOrder preOrder = new PreOrder();
        preOrder.setSName(sn);
        preOrder.setSProvince(provineNameArea.getSName());
        preOrder.setSCity(cityNameArea.getSName());
        preOrder.setSArea(sAreaName);
        preOrder.setSAddress(address);
        preOrder.setSMobile(phone);
        preOrder.setSReceiverName(name);
        preOrderService.updatePreOrderAddress(preOrder);

        String provinceID = province;
        String cityID = city;
        String areaID = area;

        OrderAddress orderAddress = null;
        for (Order order : orderList) {
            Integer orderID = order.getLID();
            orderAddress = new OrderAddress();
            orderAddress.setOrderID(orderID);
            orderAddress.setProvinceID(provinceID);
            orderAddress.setCityID(cityID);
            orderAddress.setAreaID(areaID);
            orderAddress.setSAddress(address);
            orderAddress.setSMobile(phone);
            orderAddress.setSName(name);

            orderAddressService.updateOrderAddress(orderAddress);
        }
    }

    @Override
    public List<OrderStatusModel> getOrderStatusListByOrder(List<Order> orderList) throws ExceptionUtil {
        OrderStatusModel orderStatusModel = null;

        List<OrderStatusModel> orderStatusList = new ArrayList<>();
        Map<Integer, List<OrderLogistics>> dMap = new HashMap<>();

        for (Order order : orderList) {
            List<OrderLogistics> orderLogisticsList = orderLogisticsService.getOrderLogicByOrderID(order.getLID());
            if (orderLogisticsList != null && orderLogisticsList.size() > 0) {
                for (OrderLogistics orderLogistics : orderLogisticsList) {
                    String orderDetailID = orderLogistics.getSOrderDetailID();

                    //去掉头尾,替换中间的分号为逗号
                    orderDetailID = orderDetailID.substring(1, orderDetailID.length() - 1);
                    List<String> orderIDList = new ArrayList<>();
                    if (orderDetailID.indexOf(";") > -1) {
                        String[] orderIDArr = StringUtils.split(orderDetailID, ";");
                        for (String str : orderIDArr) {
                            orderIDList.add(str);
                        }
                    } else {
                        orderIDList.add(orderDetailID);
                    }

                    for (String str : orderIDList) {
                        Integer oDetailID = Integer.parseInt(str);
                        OrderDetail orderDetail = orderDetailService.getOrderDetailByID(oDetailID);
                        Integer productID = orderDetail.getProductID();

                        if (!dMap.containsKey(productID)) {
                            List<OrderLogistics> dList = new ArrayList<>();
                            dList.add(orderLogistics);
                            dMap.put(productID, dList);
                        } else {
                            List<OrderLogistics> dList = dMap.get(productID);
                            dList.add(orderLogistics);
                        }
                    }
                }
            }

            List<OrderDetail> orderDetailList = orderDetailService.getOrderDetailByOrderID(order.getLID());

            for (OrderDetail orderDetail : orderDetailList) {
                orderStatusModel = new OrderStatusModel();
                Integer pID = orderDetail.getProductID();
                orderStatusModel.setpID(pID);
                Date dShipDate = orderDetail.getDShipDate();
                if (dShipDate == null) {
                    continue;
                }

                List<OrderLogistics> eList = dMap.get(pID);

                List<ExpressModel> expressModelList = new ArrayList<>();
                ExpressModel expressModel = null;
                for (OrderLogistics orderLogistics : eList) {
                    expressModel = new ExpressModel();
                    String code = orderLogistics.getSExpressCompany();
                    if (code == null) {
                        throw new ExceptionUtil(StatusEnum.SYS_ERROR);
                    }

                    ExpressCompany expressCompany = expressCompanyService.getIDByCode(code);
                    if (expressCompany == null) {
                        throw new ExceptionUtil(StatusEnum.EXPRESS_COMPANY_NOT_EXIST);
                    }

                    String com = expressCompany.getId();
                    expressModel.setExpressnum(orderLogistics.getSExpressNo());
                    expressModel.setExpresscom(com);
                    expressModel.setDate(DateUtils.formatYMDHMSDate(orderLogistics.getDShipDate()));

                    expressModelList.add(expressModel);
                }

                orderStatusModel.setExpress(expressModelList);
                orderStatusList.add(orderStatusModel);
            }
        }

        return orderStatusList;
    }

    @Override
    public void updateRefundStatus(Order order) {
        orderMapper.updateRefundStatus(order);
    }

    @Override
    public void fix() throws ExceptionUtil {
        List<OrderAddressModel> areaList = orderAddressService.getOrderAddressList();

        OrderAddress orderAddress = null;
        for (OrderAddressModel orderAddressModel : areaList) {
            orderAddress = new OrderAddress();
            orderAddress.setLID(orderAddressModel.getlID());
            orderAddress.setAreaID(orderAddressModel.getID());

            try {
                orderAddressService.updateOrderAddressArea(orderAddress);
            } catch (RuntimeException e) {
                System.out.println(e);
            }

            try {
                Thread.sleep(1000);
            } catch (InterruptedException e) {
                throw new ExceptionUtil(StatusEnum.SYS_ERROR);
            }
        }
    }
}
