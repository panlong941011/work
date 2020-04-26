package group.dny.api.component.aop;

import com.alibaba.fastjson.JSON;
import com.alibaba.fastjson.JSONObject;
import org.aspectj.lang.JoinPoint;
import org.aspectj.lang.annotation.AfterReturning;
import org.aspectj.lang.annotation.Aspect;
import org.aspectj.lang.annotation.Before;
import org.aspectj.lang.annotation.Pointcut;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;
import org.springframework.stereotype.Component;
import org.springframework.web.context.request.RequestContextHolder;
import org.springframework.web.context.request.ServletRequestAttributes;

import javax.servlet.http.HttpServletRequest;

@Aspect
@Component
public class ControllerAspect {
    private static Logger logger = LoggerFactory.getLogger(ControllerAspect.class);

    @Pointcut("execution(public * group.dny.api.controller..*.*(..)) && @annotation(group.dny.api.component.aop.ReqLogAnnotation)")
    public void reqLog() {
    }

    @Before("reqLog()")
    public void doBefore(JoinPoint joinPoint) {
        //获取请求报文头部元数据
        ServletRequestAttributes requestAttributes = (ServletRequestAttributes) RequestContextHolder.getRequestAttributes();
        //获取请求对象
        HttpServletRequest request = requestAttributes.getRequest();

        logger.info("请求url:" + request.getRequestURL());
        logger.info("请求参数:" + JSON.toJSONString(joinPoint.getArgs()));
    }

    @AfterReturning(returning = "ret", pointcut = "reqLog()")
    public void doAfter(Object ret) {
        logger.info("返回数据:" + JSONObject.toJSONString(ret));
    }

//
//    @Around("reqLog()")
//    public Object doAround(ProceedingJoinPoint joinPoint) {
//        ServletRequestAttributes requestAttributes = (ServletRequestAttributes) RequestContextHolder.getRequestAttributes();
//        HttpServletRequest request = requestAttributes.getRequest();
//
//        CatContext catContext = new CatContext();
//        catContext.addProperty(Cat.Context.ROOT, request.getHeader(CatHttpConstants.CAT_HTTP_HEADER_ROOT_MESSAGE_ID));
//        catContext.addProperty(Cat.Context.PARENT, request.getHeader(CatHttpConstants.CAT_HTTP_HEADER_PARENT_MESSAGE_ID));
//        catContext.addProperty(Cat.Context.CHILD, request.getHeader(CatHttpConstants.CAT_HTTP_HEADER_CHILD_MESSAGE_ID));
//
//        Cat.logRemoteCallServer(catContext);
//        String url = request.getRequestURL().toString();
//        Transaction t = Cat.newTransaction(CatConstants.TYPE_URL, url);
//        Object exeObj = null;
//        try {
//            Cat.logEvent("Controller.method", request.getMethod(), Message.SUCCESS, request.getRequestURL().toString());
//            Cat.logEvent("Controller.client", request.getRemoteHost());
//            exeObj = joinPoint.proceed();
//            t.setStatus(Transaction.SUCCESS);
//        } catch (Throwable ex) {
//            t.setStatus(ex);
//            Cat.logError(ex);
//        } finally {
//            t.complete();
//            return exeObj;
//        }
//    }
}
