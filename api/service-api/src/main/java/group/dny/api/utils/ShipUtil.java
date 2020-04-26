package group.dny.api.utils;

import group.dny.api.entity.ShipTemplateDetail;

import java.math.BigDecimal;

public class ShipUtil {
    public static Float getShipMoney(ShipTemplateDetail detail) {
        BigDecimal shipMoney = detail.getFPostage();
        if (detail.getCountBuy().compareTo(detail.getLStart()) == 1) {
            BigDecimal startValue = ArithUtil.subRounding(detail.getCountBuy(), detail.getLStart(), 3);
            BigDecimal eachCountValue = ArithUtil.divRounding(startValue, detail.getLPlus(), 3);
            Double eachCount = Math.ceil(eachCountValue.doubleValue());
            BigDecimal fPostageplus = detail.getFPostageplus();
            if (fPostageplus == null) fPostageplus = BigDecimal.valueOf(0);
            BigDecimal mulValue = ArithUtil.mulRounding(eachCount, fPostageplus, 3);
            shipMoney = ArithUtil.addRounding(shipMoney, mulValue, 3);
        }

        return shipMoney.floatValue();
    }
}
