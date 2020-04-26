package group.dny.api.dto;

import group.dny.api.component.constant.StatusEnum;

public class ResultDTO<T> {
    private Integer status;
    private String message;
    private T data;

    public ResultDTO() {
    }

    public ResultDTO(Integer status, String message, T data) {
        this.status = status;
        this.message = message;
        this.data = data;
    }

    public ResultDTO(StatusEnum statusEnum) {
        this.status = Integer.parseInt(statusEnum.getKey());
        this.message = statusEnum.getValue();
        this.data = null;
    }

    public ResultDTO(StatusEnum statusEnum, T data) {
        this.status = Integer.parseInt(statusEnum.getKey());
        this.message = statusEnum.getValue();
        this.data = data;
    }

    public Integer getStatus() {
        return status;
    }

    public void setStatus(Integer status) {
        this.status = status;
    }

    public String getMessage() {
        return message;
    }

    public void setMessage(String message) {
        this.message = message;
    }

    public T getData() {
        return data;
    }

    public void setData(T data) {
        this.data = data;
    }

    public void setResultMessage(StatusEnum status) {
        this.status = Integer.parseInt(status.getKey());
        this.message = status.getValue();
    }

}
