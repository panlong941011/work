package group.dny.cloud.service;

import org.mybatis.spring.annotation.MapperScan;
import org.springframework.boot.SpringApplication;
import org.springframework.boot.autoconfigure.SpringBootApplication;

@SpringBootApplication
@MapperScan("group.dny.cloud.service.mapper")
public class CloudServiceApplication {
    public static void main(String[] args) {
        new SpringApplication(CloudServiceApplication.class).run(args);
    }
}
