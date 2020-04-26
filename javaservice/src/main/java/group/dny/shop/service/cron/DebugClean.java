package group.dny.shop.service.cron;

import group.dny.shop.service.service.impl.ShopDebugServiceImpl;
import org.quartz.DisallowConcurrentExecution;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.beans.factory.annotation.Configurable;
import org.springframework.beans.factory.annotation.Value;
import org.springframework.scheduling.annotation.EnableScheduling;
import org.springframework.scheduling.annotation.Scheduled;
import org.springframework.stereotype.Component;

import java.io.File;
import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.Date;

/**
 * 定时清理Debug文件
 */

@Component
@Configurable
@EnableScheduling
@DisallowConcurrentExecution
public class DebugClean {

    //日志
    private final static Logger logger = LoggerFactory.getLogger(DebugClean.class);

    @Autowired
    private ShopDebugServiceImpl shopDebugService;

    @Value("${dny.shop.debug.savepath}")
    private String sDebugSavePath;

    private static boolean deleteDir(File dir) {
        if (dir.isDirectory()) {
            String[] children = dir.list();

            //递归删除目录中的子目录下
            for (int i = 0; i < children.length; i++) {
                boolean success = deleteDir(new File(dir, children[i]));
                if (!success) {
                    return false;
                }
            }
        }

        // 目录此时为空，可以删除
        return dir.delete();
    }

    /**
     * 每天凌晨三点清理
     */
    @Scheduled(cron = "0 0 3 * * * ")
    public void exec() throws ParseException {

        logger.info("开始清理debug数据");

        SimpleDateFormat df = new SimpleDateFormat("yyyy-MM-dd");
        long lTodayTime = new Date().getTime() / 1000;

        File file0 = new File(sDebugSavePath);
        String[] arrDebugDir = file0.list();
        for (String sDirName : arrDebugDir) {
            File dir = new File(sDebugSavePath + "/" + sDirName);

            //超过十天的目录，都删除
            if (lTodayTime - df.parse(sDirName).getTime() / 1000 > 10 * 86400) {
                logger.info("删除目录：" + sDirName);
                deleteDir(dir);//删除目录
            }
        }

        logger.info("清理数据库数据.....");
        shopDebugService.cleanExpire(10);//清理10天前的数据
        logger.info("清理数据库数据完毕.....");

        logger.info("debug数据清理完毕");
    }
}
