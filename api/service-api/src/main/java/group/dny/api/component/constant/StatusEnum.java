package group.dny.api.component.constant;

public enum StatusEnum {
    SUCCESS("10000", "操作成功"),
    FAILURE("10001", "操作失败"),
    PRODUCT_NOT_EXIST("10002", "商品不存在"),
    PRODUCT_QUANTITY_NOT_ZERO("10003", "商品数量不能为零"),
    PROVINCE_NOT_EXIST("10004", "省份不存在"),
    CITY_NOT_EXIST("10005", "城市不存在"),
    PRODUCT_PRICE_NOT_ZERO("10006", "商品价格不能为零"),
    SHIP_TEMPLATE_NOT_EXIST("10007", "运费模板不存在"),
    AREA_NOI_SEND_PRODUCT("10008", "此地区不送货"),
    DEFAULT_SHIP_NOT_EXIST("10009", "默认运费不存在"),
    SHIP_FREE("10010", "免运费"),
    PRODUCT_DEL("10011", "该商品已删除"),
    PRODUCT_SALEOUT("10012", "该商品已售罄"),
    PRODUCT_ONSALE("10013", "该商品在售"),
    PRODUCT_LOW_STOCK("10014", "该商品库存不足"),
    PRODUCT_SPEC_LOW_STOCK("10015", "所选规格库存不足"),
    PRODUCT_SPEC_NOEXISTS("10016", "所选规格不存在"),
    PRODUCT_SPEC_NOSELECTED("10017", "未选择规格，请重新添加商品"),
    ORDER_UNPAID("10018", "unpaid"),//未付款
    ORDER_PAID("10019", "paid"),//已付款
    ORDER_DELIVERED("10020", "delivered"),//已发货
    ORDER_SUCCESS("10021", "success"),//交易成功
    ORDER_CLOSED("10022", "closed"),//交易关闭
    ORDER_EXCEPTION("10023", "exception"),//付款异常
    ORDER_PAY_SUCCESS("10024", "paysuccess"),//付款成功
    ORDER_SAVE_ORDER("10025", "saveorder"),//下单
    ORDER_AUTO_CLOSE("10026", "autoclose"),//自动关闭
    AREA_NOT_EXIST("10027", "地区不能为空"),
    ADDRESS_NOT_EXIST("10028", "地址不能为空"),
    BUYER_NAME_NOT_EXIST("10029", "买家姓名不能为空"),
    BUYER_PHONE_NOT_EXIST("10030", "买家电话不能为空"),
    SKU_NOT_EMPTY("10031", "sku不能为空"),
    SECKILL_NOSTART("10032", "nostart"),
    SECKILL_ONGOING("10033", "ongoing"),
    SECKILL_FINISHED("10034", "finished"),
    SECKILL_SALEOUT("10035", "已抢光"),
    SECKILL_OVERLIMIT("10036", "有秒杀商品超出限购数量，请修改数量"),
    PRODUCT_OFFSALE("10037", "该商品已下架"),
    ORDER_SN_NOT_EMPTY("10038", "订单号不能为空"),
    ORDER_NOT_EXIST("10039", "订单不存在"),
    SUPPLIER_NOT_EXIST("10040", "供应商不存在"),
    BUYER_NOT_EXIST("10041", "买家不存在"),
    ORDER_TIMEOUT("10042", "超时未付款，云订单已关闭"),
    ORDER_CREATE_FAIL("10043", "云端系统生成云订单失败"),
    TOKEN_IS_NOT_EMPTY("10044", "TOKEN不能为空"),
    TOKEN_IS_EXPIRED("10045", "TOKEN已经过期"),
    ORDER_RECREATE("10046", "订单重复"),
    ORDER_NOT_PAID("10047", "不是待发货状态下"),
    TOKEN_IS_VALIDATE("10048", "TOKEN有效无需再次登录"),
    TOKEN_IP_NOT_VALIDATE("10049", "IP受限"),
    SYS_ERROR("99999", "系统错误"),
    ORDER_STATUS_NOT_ALLOW("10050", "订单的状态不允许"),
    REFUND_RESUBMIT("10051", "退款单重复提交"),
    REFUND_ORDER_DETAIL_INREFUND("10052", "该商品的订单明细处于退款中"),
    REFUND_TYPE_ERROR("10053", "退款类型不正确，moneyandproduct不能在未发货状态下使用"),
    REFUND_AMOUNT_ERROR("10054", "退款金额有误，退款的金额不能大于商品的总金额"),
    REFUND_NOT_EXIST("10055", "退款申请不存在"),
    REFUND_DETAIL_NOT_EXIST("10056", "该商品的订单明细不处于退款中"),
    REFUND_CAN_AMOUNT_ERROR("10057", "退款金额有误，退款的金额不能大于可以退款的金额"),
    EXPRESS_COMPANY_NOT_EXIST("10058", "快递公司不存在"),
    USERLOGIN_ERROR("10059", "sAppID或sAppSec错误"),
    ORDER_STATUS_NOT_EXIST("10060", "没有此订单状态"),
    BUYER_AMOUNT_NOT_ENOUGH("10061", "采购款不足"),
    ;

    private final String key;
    private final String value;

    private StatusEnum(String key, String value) {
        this.key = key;
        this.value = value;
    }

    //根据key获取枚举
    public static StatusEnum getEnumByKey(String key) {
        if (null == key) {
            return null;
        }
        for (StatusEnum temp : StatusEnum.values()) {
            if (temp.getKey().equals(key)) {
                return temp;
            }
        }
        return null;
    }

    public String getKey() {
        return key;
    }

    public String getValue() {
        return value;
    }


}
