package group.dny.api.entity;

import java.math.BigDecimal;

public interface ISkuBase {
    public String getSkuName();

    public BigDecimal getSkuPrice();

    public Integer getSkuStock();
}
