package group.dny.api.configuration;

import org.springframework.boot.context.properties.ConfigurationProperties;
import org.springframework.stereotype.Component;

/**
 * @Description TODO
 * @ClassName BusinessConfiguration
 * @Author lizhengfan
 * @Date 2019/5/31 16:30
 * @Version 1.0.0
 **/
@Component
@ConfigurationProperties(prefix = "business")
public class BusinessConfiguration {
    String casurl;

    public String getCasurl() {
        return casurl;
    }

    public void setCasurl(String casurl) {
        this.casurl = casurl;
    }
}
