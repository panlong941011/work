package group.dny.api.service;

import com.baomidou.mybatisplus.extension.service.IService;
import group.dny.api.component.model.*;
import group.dny.api.entity.Order;
import group.dny.api.utils.ExceptionUtil;

import java.math.BigDecimal;
import java.util.List;
import java.util.Map;

/**
 * <p>
 * 服务类
 * </p>
 *
 * @author lizhengfan
 * @since 2019-03-21
 */
public interface IOrderService extends IService<Order> {
    //获取订单运费
    BigDecimal getOrderShip(List<OrderTemplateModel> dataList, String provinceName, String cityName) throws ExceptionUtil;

    //订单运费所需要的数据
    List<OrderTemplateModel> getOrderData(List<OrderDataModel> list, Boolean isSupplier);

    //批量判断订单中商品的有效性
    List<ProductInvalidModel> bInvalidStatus(List<ProductSkuModel> list, String provinceName, String cityName, Boolean isSupplier);

    //下单确认，生成真正的订单信息
    void confirmPay(String sn) throws ExceptionUtil;

    //插入订单信息
    int insert(Order entity);

    //根据ID修改订单地址
    void updateOrderAddressByID(Order order);

    //订单费用计算
    void orderPayment(Integer orderID) throws ExceptionUtil;

    //根据ID获取订单
    Order getOrderByID(Integer orderID);

    //根据ID更新订单状态
    void updateOrderStatusByID(Order order);

    //根据供应删ID获取待结算金额
    BigDecimal getWaitMoneyBySupplierID(Integer supplierID);

    //根据sClientSN获取订单列表
    List<Order> getOrderBySn(String sn);

    //确认收货
    void confirmReceived(String sn) throws ExceptionUtil;

    //更新订单收货信息
    void updateOrderReceiveInfo(Order order);

    //修改订单地址
    void modifyAddress(OrderModel orderModel) throws ExceptionUtil;

    List<OrderStatusModel> getOrderStatusListByOrder(List<Order> orderList) throws ExceptionUtil;

    void updateRefundStatus(Order order);

    Map<String, Object> submit(OrderModel model) throws ExceptionUtil;

    Map<String, Object> ship(String sku, String cityName, String provinceName, Boolean isSupplier) throws ExceptionUtil;

    List<OrderStatusModel> status(String sn) throws ExceptionUtil;

    void fix() throws ExceptionUtil;
}
