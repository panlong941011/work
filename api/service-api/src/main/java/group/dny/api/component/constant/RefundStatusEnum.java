package group.dny.api.component.constant;

public enum RefundStatusEnum {
    REFUNDING("refunding", "退款中"),
    SUCCESS("success", "退款成功"),
    CLOSED("closed", "退款关闭");

    private final String key;
    private final String value;

    private RefundStatusEnum(String key, String value) {
        this.key = key;
        this.value = value;
    }

    //根据key获取枚举
    public static RefundStatusEnum getEnumByKey(String key) {
        if (null == key) {
            return null;
        }
        for (RefundStatusEnum temp : RefundStatusEnum.values()) {
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
