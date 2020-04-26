package group.dny.api.component.constant;

public enum RefundTypeEnum {
    ONLYMONEY("onlymoney"), MONEYANDPRODUCT("moneyandproduct");

    private final String type;

    private RefundTypeEnum(String type) {
        this.type = type;
    }

    public String getType() {
        return type;
    }
}
