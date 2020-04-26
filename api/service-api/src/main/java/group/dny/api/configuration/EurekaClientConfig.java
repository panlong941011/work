package group.dny.api.configuration;

import org.springframework.beans.factory.annotation.Value;
import org.springframework.boot.autoconfigure.EnableAutoConfiguration;
import org.springframework.cloud.netflix.eureka.EurekaClientConfigBean;
import org.springframework.context.annotation.Bean;
import org.springframework.context.annotation.Configuration;

import java.util.Map;


@EnableAutoConfiguration
@Configuration
public class EurekaClientConfig {

    @Value("${eureka.client.service-url.defaultZone}")
    private String sDefaultZone;

    @Bean
    public EurekaClientConfigBean getEurekaClientConfig() {
        EurekaClientConfigBean config = new EurekaClientConfigBean();

        Map<String, String> mapServiceUrl = config.getServiceUrl();
        mapServiceUrl.put("defaultZone", sDefaultZone);

        config.setServiceUrl(mapServiceUrl);

        return config;
    }

}
