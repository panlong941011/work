package eureka.api.dny.group;

import org.springframework.boot.SpringApplication;
import org.springframework.boot.autoconfigure.SpringBootApplication;
import org.springframework.cloud.netflix.eureka.server.EnableEurekaServer;

//@EnableApolloConfig
@EnableEurekaServer
@SpringBootApplication
public class EurekaServerApplication {
    //    @ApolloConfig
//    private Config config;
    public static void main(String[] args) {
        SpringApplication.run(EurekaServerApplication.class, args);
    }
//    @com.ctrip.framework.apollo.spring.annotation.ApolloConfigChangeListener
//    private void onChange(ConfigChangeEvent changeEvent) {
//        refreshInfo();
//    }
//
//    @PostConstruct
//    private void refreshInfo() {
//        Set<String> keyNames = config.getPropertyNames();
//        for (String key : keyNames) {
//            String iTimeout = config.getProperty(key, "timeout");
//            System.out.print(key + "-->");
//            System.out.println(iTimeout);
//        }
//    }
}