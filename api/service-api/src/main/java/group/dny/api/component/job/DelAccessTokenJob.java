package group.dny.api.component.job;

import group.dny.api.service.IAccessTokenService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.scheduling.annotation.EnableScheduling;
import org.springframework.stereotype.Component;

@Component
@EnableScheduling
public class DelAccessTokenJob {
    @Autowired
    IAccessTokenService accessTokenService;

    public void task() {
        accessTokenService.delAccessTokenEachWeek();
    }
}
