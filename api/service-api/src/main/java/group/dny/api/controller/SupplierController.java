package group.dny.api.controller;


import com.baomidou.mybatisplus.core.metadata.IPage;
import group.dny.api.component.aop.ReqLogAnnotation;
import group.dny.api.component.constant.StatusEnum;
import group.dny.api.dto.ResultDTO;
import group.dny.api.entity.Supplier;
import group.dny.api.service.ISupplierService;
import group.dny.api.utils.ExceptionUtil;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.http.MediaType;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RequestParam;
import org.springframework.web.bind.annotation.RestController;

import java.util.HashMap;
import java.util.Map;


/**
 * <p>
 * 前端控制器
 * </p>
 *
 * @author lizhengfan
 * @since 2019-03-21
 */
@RestController
@RequestMapping(value = "/v1/supplier")
public class SupplierController extends BaseController {
    @Autowired
    ISupplierService supplierService;

    /**
     * 获取供应商列表
     *
     * @return
     */
    @ReqLogAnnotation
    @RequestMapping(value = "/list", produces = MediaType.APPLICATION_JSON_VALUE)
    public ResultDTO list(@RequestParam("keyword") String keyword, @RequestParam("page") Integer page, @RequestParam("pagesize") Integer pagesize) {
        try {
            IPage<Supplier> list = supplierService.getSupplierList(keyword, page, pagesize);
            result.setResultMessage(StatusEnum.SUCCESS);
            result.setData(list);
        } catch (ExceptionUtil e) {
            StatusEnum status = e.getValue();
            result.setResultMessage(status);
        }

        return result;
    }

    /**
     * 获取供应商
     *
     * @return
     */
    @ReqLogAnnotation
    @RequestMapping(value = "/info", produces = MediaType.APPLICATION_JSON_VALUE)
    public ResultDTO info(@RequestParam("lID") Integer lID) {
        Supplier supplier = supplierService.getSupplierByID(lID);
        if (supplier != null) {
            result.setResultMessage(StatusEnum.SUCCESS);
            Map<String, Object> map = new HashMap<>();
            map.put("lID", supplier.getLID());
            map.put("sName", supplier.getSName());
            map.put("sMobile", supplier.getSMobile());
            map.put("sPicPath", supplier.getSPicPath());

            result.setData(map);
        } else {
            result.setResultMessage(StatusEnum.SUPPLIER_NOT_EXIST);
        }

        return result;
    }
}
