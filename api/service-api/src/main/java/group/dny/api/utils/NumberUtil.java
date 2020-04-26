package group.dny.api.utils;

import java.math.BigDecimal;
import java.text.NumberFormat;

public class NumberUtil {
    public static String formatNumber(Float number, int scale) {
        NumberFormat nf = NumberFormat.getNumberInstance();
        nf.setMaximumFractionDigits(2);
        String sNumber = nf.format(number);

        return sNumber;
    }

    public static BigDecimal nullToZero(BigDecimal number) {
        if (number == null) return BigDecimal.valueOf(0);
        else return number;
    }
}
