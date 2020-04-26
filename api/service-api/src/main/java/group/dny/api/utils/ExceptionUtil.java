package group.dny.api.utils;

import group.dny.api.component.constant.StatusEnum;

import java.util.Map;

public class ExceptionUtil extends RuntimeException {
    StatusEnum status;
    Map<String, Object> map = null;

    public ExceptionUtil() {
        super();
    }

    public ExceptionUtil(String message, StatusEnum status) {
        super(message);
        this.status = status;
    }

    public ExceptionUtil(StatusEnum status) {
        this.status = status;
    }

    public ExceptionUtil(Map<String, Object> map, StatusEnum status) {
        this.map = map;
        this.status = status;
    }

    public Map<String, Object> getMap() {
        return map;
    }

    public void setMap(Map<String, Object> map) {
        this.map = map;
    }

    public StatusEnum getValue() {
        return status;
    }
}
