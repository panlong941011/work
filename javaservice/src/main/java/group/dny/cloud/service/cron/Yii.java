package group.dny.cloud.service.cron;

import org.quartz.DisallowConcurrentExecution;
import org.quartz.PersistJobDataAfterExecution;
import org.springframework.beans.factory.annotation.Configurable;
import org.springframework.scheduling.annotation.EnableScheduling;
import org.springframework.scheduling.annotation.Scheduled;
import org.springframework.stereotype.Component;

/**
 * 执行YII的计划任务
 */
@Component
@Configurable
@EnableScheduling
@DisallowConcurrentExecution
@PersistJobDataAfterExecution
public class Yii extends YiiConsole {

    /**
     * 每1分钟第5秒执行
     */
    @Scheduled(cron = "5 */1 * * * *")
    public void productstockchange() {
        exec("执行商品库存变化计划任务", "productstockchange/closechange");
    }

    /**
     * 每1分钟第10秒执行
     */
    @Scheduled(cron = "10 */1 * * * *")
    public void returnsTimeout() {
        exec("执行退货超时计划任务", "returns/timeout");
    }

    /**
     * 每1分钟第15秒执行
     */
    @Scheduled(cron = "15 */1 * * * *")
    public void refundTimeout() {
        exec("执行退款超时计划任务", "refund/timeout");
    }

    /**
     * 每10分钟第3秒执行
     */
    @Scheduled(cron = "3 */10 * * * *")
    public void updatesigndate() {
        exec("执行确认订单签收时间计划任务", "order/updatesigndate");
    }

    /**
     * 每10分钟第6秒执行
     */
    @Scheduled(cron = "6 */10 * * * *")
    public void confirmreceive() {
        exec("执行确认收货计划任务", "order/confirmreceive");
    }

    /**
     * 每10分钟第9秒执行
     */
    @Scheduled(cron = "9 */10 * * * *")
    public void pushpromotionupdate() {
        exec("执行推送促销更新计划任务", "salespromotion/pushpromotionupdate");
    }

    /**
     * 每10分钟第12秒执行
     */
    @Scheduled(cron = "12 */10 * * * *")
    public void downloadfromlsj() {
        exec("执行来三斤商品下载计划任务", "product/downloadfromlsj");
    }

    /**
     * 每个月7点
     */
    @Scheduled(cron = "00 00 07 1 * *")
    public void kingdataOrder() {
        exec("执行金蝶订单导出计划任务", "kingdata/order");
    }

    /**
     * 每2个小时第一分钟第1秒执行
     */
    @Scheduled(cron = "1 1 0/2 * * *")
    public void failshippush() {
        exec("执行订单关闭计划任务", "order/failshippush");
    }

    /**
     * 每2个小时第二分钟第2秒执行
     */
    @Scheduled(cron = "2 2 0/2 * * *")
    public void ordernotrace() {
        exec("执行快递订单追踪计划任务", "express/ordernotrace");
    }

    /**
     * 每6个小时执行
     */
    @Scheduled(cron = "3 3 0/6 * * *")
    public void expressSync() {
        exec("执行快递同步计划任务", "express/sync");
    }
}
