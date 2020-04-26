package group.dny.api.configuration;

import org.springframework.boot.context.properties.ConfigurationProperties;
import org.springframework.stereotype.Component;

import java.util.List;

@Component
@ConfigurationProperties(prefix = "wholesale")
public class WholesaleConfig {
    private List<String> allowIP;

    public List<String> getAllowIP() {
        return allowIP;
    }

    public void setAllowIP(List<String> allowIP) {
        this.allowIP = allowIP;
    }
}
