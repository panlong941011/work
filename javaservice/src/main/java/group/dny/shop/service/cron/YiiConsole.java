package group.dny.shop.service.cron;

import org.quartz.Scheduler;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.beans.factory.annotation.Qualifier;
import org.springframework.beans.factory.annotation.Value;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStreamReader;

public class YiiConsole {

    //日志
    protected final static Logger logger = LoggerFactory.getLogger(YiiConsole.class);

    @Value("${dny.shop.root.path}")
    private String sRootPath;

    /**
     * 运行控制台
     */
    protected void exec(String sTaskName, String sCommand) {

        logger.info(sTaskName);

        try {
            final Process p = Runtime.getRuntime().exec("php " + getRootPath() + "/shop " + sCommand);
            new Thread(() -> {
                BufferedReader br = new BufferedReader(new InputStreamReader(p.getInputStream()));
                StringBuilder sb = new StringBuilder();
                String line = null;
                try {
                    while ((line = br.readLine()) != null) {
                        sb.append(line).append("\n");
                    }
                    br.close();
                    logger.info(sb.toString());
                } catch (IOException e) {
                    e.printStackTrace();
                }
            }).start();

            BufferedReader br = null;
            br = new BufferedReader(new InputStreamReader(p.getErrorStream()));
            String line = null;
            StringBuilder sb = new StringBuilder();
            while ((line = br.readLine()) != null) {
                sb.append(line).append("\n");
            }
            p.waitFor();
            br.close();
            p.destroy();
            logger.info(sb.toString());
        } catch (Exception e) {
            e.printStackTrace();
            logger.error(e.getMessage());
        }
    }

    /**
     * 大农云商城根目录
     *
     * @return
     */
    protected String getRootPath() {
        return sRootPath;
    }
}