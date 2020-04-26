package group.dny.api.component.constant;

public enum OrderStatusEnum {
    PAID("paid", "未发货"),
    DELIVERD("delivered", "已发货"),
    SUCCESS("success", "交易成功"),
    CLOSED("closed", "交易关闭"),
    UNPAID("unpaid", "未付款"),
    ;

    private final String key;
    private final String value;

    private OrderStatusEnum(String key, String value) {
        this.key = key;
        this.value = value;
    }

    //根据key获取枚举
    public static OrderStatusEnum getEnumByKey(String key) {
        if (null == key) {
            return null;
        }
        for (OrderStatusEnum temp : OrderStatusEnum.values()) {
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
