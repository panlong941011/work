package group.dny.shop.service;

import org.mybatis.spring.annotation.MapperScan;
import org.springframework.boot.SpringApplication;
import org.springframework.boot.autoconfigure.SpringBootApplication;
import org.springframework.cloud.netflix.sidecar.EnableSidecar;

@EnableSidecar
@SpringBootApplication
@MapperScan("group.dny.shop.service.mapper")
public class ShopServiceApplication {
    public static void main(String[] args) {
        new SpringApplication(ShopServiceApplication.class).run(args);
    }
}
