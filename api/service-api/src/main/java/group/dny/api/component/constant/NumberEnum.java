package group.dny.api.component.constant;

public enum NumberEnum {
    ONE(1), TWO(2), THREE(3), FOUR(4), FIVE(5), SIX(6), SEVEN(7), EIGHT(8), NINE(9), TEN(10), MINER(-1);

    private final Integer number;

    private NumberEnum(Integer number) {
        this.number = number;
    }

    public Integer getNumber() {
        return number;
    }
}
