package group.dny.api.configuration;

import org.springframework.boot.web.servlet.FilterRegistrationBean;
import org.springframework.context.annotation.Bean;
import org.springframework.context.annotation.Configuration;
import org.springframework.web.filter.DelegatingFilterProxy;

import javax.servlet.Filter;

@Configuration
public class WebConfig {

    @Bean
    public Filter tokenFilter() {
        return new TokenFilter();
    }

    @Bean
    FilterRegistrationBean tokenFilterRegistor() {
        FilterRegistrationBean filterReg = new FilterRegistrationBean();
        filterReg.setFilter(new DelegatingFilterProxy("tokenFilter"));
        filterReg.setOrder(1);
        filterReg.setName("tokenFilter");
        filterReg.addUrlPatterns("/*");
        return filterReg;
    }

}
