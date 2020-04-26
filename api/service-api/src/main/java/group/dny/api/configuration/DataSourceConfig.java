package group.dny.api.configuration;

import com.alibaba.druid.pool.DruidDataSource;
import org.springframework.beans.factory.annotation.Value;
import org.springframework.boot.autoconfigure.EnableAutoConfiguration;
import org.springframework.context.annotation.Bean;
import org.springframework.context.annotation.Configuration;

import javax.sql.DataSource;
import java.sql.SQLException;

@EnableAutoConfiguration
@Configuration
public class DataSourceConfig {

    @Value("${datasource.url}")
    private String url;

    @Value("${datasource.username}")
    private String username;

    @Value("${datasource.password}")
    private String password;

    @Bean
    public DataSource getDataSource() throws SQLException {
        DruidDataSource dataSource = new DruidDataSource();
        //url = "jdbc:mysql://192.168.0.206:3306/dnycloud_beta?useUnicode=true&characterEncoding=utf-8&useSSL=false";
        //username = "root";
        //password = "123456";

        dataSource.setUrl(url);
        dataSource.setUsername(username);// 用户名
        dataSource.setPassword(password);// 密码
        dataSource.setDriverClassName("com.mysql.jdbc.Driver");
        dataSource.setDbType("mysql");
        dataSource.setFilters("stat,wall,log4j");

        return dataSource;
    }

}