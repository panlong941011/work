package group.dny.api.configuration;


import group.dny.api.component.job.DelAccessTokenJob;
import org.quartz.JobDetail;
import org.quartz.Trigger;
import org.springframework.context.annotation.Bean;
import org.springframework.context.annotation.Configuration;
import org.springframework.scheduling.quartz.CronTriggerFactoryBean;
import org.springframework.scheduling.quartz.MethodInvokingJobDetailFactoryBean;
import org.springframework.scheduling.quartz.SchedulerFactoryBean;

@Configuration
public class QuartzConfiguration {
    @Bean(name = "delAccessJobDetail")
    public MethodInvokingJobDetailFactoryBean delAccessJobDetail(DelAccessTokenJob delAccessTokenJob) {
        MethodInvokingJobDetailFactoryBean jobDetail = new MethodInvokingJobDetailFactoryBean();
        jobDetail.setConcurrent(true);
        jobDetail.setTargetObject(delAccessTokenJob);
        jobDetail.setTargetMethod("task");
        return jobDetail;
    }

    @Bean(name = "delAccessJobTrigger")
    public CronTriggerFactoryBean delAccessJobTrigger(JobDetail delAccessJobDetail) {
        CronTriggerFactoryBean trigger = new CronTriggerFactoryBean();
        trigger.setJobDetail(delAccessJobDetail);
        trigger.setCronExpression("0 */5 * * *  ?");
        return trigger;
    }

    @Bean(name = "scheduler")
    public SchedulerFactoryBean schedulerFactory(Trigger delAccessTrigger) {
        SchedulerFactoryBean bean = new SchedulerFactoryBean();
        bean.setStartupDelay(5);
        bean.setTriggers(delAccessTrigger);
        return bean;
    }
}