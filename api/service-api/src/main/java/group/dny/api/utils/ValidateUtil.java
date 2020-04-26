package group.dny.api.utils;

import group.dny.api.component.constant.StatusEnum;

public class ValidateUtil {
    public static StatusEnum userInfoValidate(String province, String city, String area, String address, String name, String phone) {
        if (province == null || province.length() <= 0) {
            return StatusEnum.PROVINCE_NOT_EXIST;
        }

        if (city == null || city.length() <= 0) {
            return StatusEnum.CITY_NOT_EXIST;
        }

//        if (area == null || area.length() <= 0) {
//            return StatusEnum.AREA_NOT_EXIST;
//        }

        if (address == null || address.length() <= 0) {
            return StatusEnum.ADDRESS_NOT_EXIST;
        }

        if (name == null || name.length() <= 0) {
            return StatusEnum.BUYER_NAME_NOT_EXIST;
        }

        if (phone == null || phone.length() <= 0) {
            return StatusEnum.BUYER_PHONE_NOT_EXIST;
        }

        return StatusEnum.SUCCESS;
    }
}
