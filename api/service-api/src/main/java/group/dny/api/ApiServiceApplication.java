package group.dny.api;

import org.mybatis.spring.annotation.MapperScan;
import org.springframework.boot.SpringApplication;
import org.springframework.boot.autoconfigure.SpringBootApplication;
import org.springframework.cloud.netflix.eureka.EnableEurekaClient;
import org.springframework.transaction.annotation.EnableTransactionManagement;

@EnableTransactionManagement
@EnableEurekaClient
@SpringBootApplication
@MapperScan("group.dny.api.mapper")
public class ApiServiceApplication {
    public static void main(String[] args) {
        SpringApplication application = new SpringApplication(ApiServiceApplication.class);
        application.run(args);
    }
}
