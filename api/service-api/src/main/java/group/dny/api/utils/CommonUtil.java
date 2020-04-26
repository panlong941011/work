package group.dny.api.utils;

import org.joda.time.DateTime;

import java.util.Random;

public class CommonUtil {
    /**
     * 随机生成订单号
     *
     * @return
     */
    public static String createOrderSn() {
        DateTime dateTime = new DateTime();
        String dateTimeStr = dateTime.toString("yyyyMMddHHmmss");

        Random rand = new Random();
        Integer MAX = 99999;
        Integer MIN = 10000;
        int randNumber = rand.nextInt(MAX - MIN + 1) + MIN;

        return dateTimeStr + randNumber;
    }

    public static String tokenMaker() {
        long millisTime = System.currentTimeMillis();
        Random random = new Random();
        int randnum = random.nextInt(4);
        return millisTime + "" + randnum;
    }
}
