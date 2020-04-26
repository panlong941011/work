<style>
    .form-group input, .form-group select{
        width: 300px;
        height: 30px;
    }

</style>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <h4 class="modal-title">发货地址选择快递</h4>
</div>
<div class="modal-body" style="overflow: auto; height: 420px;">
    <form name="shipform" class="form-horizontal" onsubmit="return false;">
        <input  type="hidden" name="ids" id="ids" value="<?=$ids?>">

        <!--快递业务-->
        <input type="hidden" id="ExpressBusinessID" name="ExpressBusinessID" value="<?=$info['ExpressBusinessID']?>">

        <!-- 结算方式-->
        <input type="hidden" id="ClearingWayID" name="ClearingWayID" value="<?=$info['ClearingWayID']?>">

        <!--快递编号：-->
        <input type="hidden" id="sKdbirdCode" name="sKdbirdCode" value="<?=$info['sKdbirdCode']?>">

        <div class="form-group">
            <lable class="col-md-3 control-label" flag="2">选择模板：</lable>
            <select name="expressCompany" id="expressCompany">
                <?php foreach ($sName as $k=>$v):?>
                    <?php if ($k==$defaultID):?>
                        <option value="<?=$k?>" selected="selected"><?=$v?></option>
                    <?php else:?>
                        <option value="<?=$k?>"><?=$v?></option>
                    <?php endif;?>
                <?php endforeach;?>
            </select>
        </div>

        <!-- 快递合作方账户-->
        <div class="form-group" id="user">
            <lable class="col-md-3 control-label" flag="2">快递合作方账户：</lable>
            <input type="text" id="expressUser" name="expressUser" value="<?=$info['sExpressName']?>" readonly="readonly">
        </div>

        <!-- 快递合作方密码-->
        <div class="form-group" id="password" >
            <lable class="col-md-3 control-label" flag="2">快递合作方密码：</lable>
            <input type="password" id="expressPwd" name="expressPwd" value="<?=$info['sExpressPassword']?>" readonly="readonly">
        </div>

        <!-- 快递合作方密钥：-->
        <div class="form-group" id="key" >
            <lable class="col-md-3 control-label" flag="2">快递合作方密钥：</lable>
            <input type="password" id="expressKey" name="expressKey" value="<?=$info['sExpressKey']?>" readonly="readonly">
        </div>

        <!-- 快递网点名称：：-->
        <div class="form-group" id="sendsite">
            <lable class="col-md-3 control-label" flag="2">快递网点名称：</lable>
            <input type="text" id="expressSendSite" name="expressSendSite" value="<?=$info['sExpressSendSite']?>" readonly="readonly">
        </div>

        <!--月结编码：-->
        <div class="form-group" id="expressCode">
            <lable class="col-md-3 control-label" flag="2">月结编码：</lable>
            <input type="text" id="sExpressCode" name="sExpressCode" value="<?=$info['sExpressCode']?>" readonly="readonly">
        </div>


        <!--快递名称：-->
        <div class="form-group">
            <lable class="col-md-3 control-label" flag="2">快递名称：</lable>
            <input type="text" id="couriername" name="couriername" value="<?=$info['courierName']?>" readonly="readonly">
        </div>

        <!--发货人：-->
        <div class="form-group">
            <lable class="col-md-3 control-label" flag="2">发货人：</lable>
            <input type="text" id="sShipper" name="sShipper" value="<?=$info['sShipper']?>" readonly="readonly">
        </div>

        <!--发货人：-->
        <div class="form-group">
            <lable class="col-md-3 control-label" flag="2">联系方式：</lable>
            <input type="text" id="sMobile" name="sMobile" value="<?=$info['sMobile']?>" readonly="readonly">
        </div>

        <!--省份：-->
        <div class="form-group">
            <lable class="col-md-3 control-label" flag="2">省份：</lable>
            <input type="text" id="ProvinceID" name="ProvinceID" value="<?=$info['province']?>" readonly="readonly">
        </div>

        <!--市：-->
        <div class="form-group">
            <lable class="col-md-3 control-label" flag="2">市：</lable>
            <input type="text" id="CityID" name="CityID" value="<?=$info['city']?>" readonly="readonly">
        </div>

        <!--县：-->
        <div class="form-group">
            <lable class="col-md-3 control-label" flag="2">县：</lable>
            <input type="text" id="AreaID" name="AreaID" value="<?=$info['area']?>" readonly="readonly">
        </div>

        <!--详细地址：-->
        <div class="form-group">
            <lable class="col-md-3 control-label" flag="2">详细地址：</lable>
            <input type="text" id="sAddress" name="sAddress" value="<?=$info['sAddress']?>" readonly="readonly">
        </div>
    </form>
    <div class="modal-footer">
        <button type="button" onclick="closeModal()" class="btn btn-outline dark">取消</button>
        <button type="button" class="btn green" id="suredeliveryorder">确定</button>
    </div>
</div>

<script>
    $(function(){
        var sExpressName = "<?=$info['sExpressName']?>";
        if(sExpressName==null||sExpressName==undefined||sExpressName==""){
            $("#user").hide();
        }

        var expressPwd = "<?=$info['sExpressPassword']?>";
        if(expressPwd==null||expressPwd==undefined||expressPwd==""){
            $("#password").hide();
        }

        var key = "<?=$info['sExpressKey']?>";
        if(key==null||key==undefined||key==""){
            $("#key").hide();
        }

        var sendsite = "<?=$info['sExpressSendSite']?>";
        if(sendsite==null||sendsite==undefined||sendsite==""){
            $("#sendsite").hide();
        }
        var ExpressCode = "<?=$info['sExpressCode']?>";
        if(ExpressCode==null||ExpressCode==undefined||ExpressCode==""){
            $("#expressCode").hide();
        }
    });

    $("#expressCompany").change(function(){
        var ID = $("#expressCompany").find("option:selected").val();
        $.ajax({
            url:'/shop/order/gettemplateinfo',
            type: 'POST',
            data:{
                id:ID
            },
            success:function(data){
                if (data.bSuccess) {
                    var data = data.data;
                    $("#ExpressBusinessID").val(data['ExpressBusinessID']);//快递业务
                    $("#ClearingWayID").val(data['ClearingWayID']);//结算方式
                    $("#sKdbirdCode").val(data['sKdbirdCode']);//快递编码
                    $("#expressUser").val(data['sExpressName']);//快递合作方账户
                    $("#expressPwd").val(data['sExpressPassword']);//快递合作方密码
                    $("#expressKey").val(data['sExpressKey']);//快递合作方密钥
                    $("#expressSendSite").val(data['sExpressSendSite']);//快递网点名称
                    $("#sExpressCode").val(data['sExpressCode']);//月结编码
                    $("#couriername").val(data['courierName']);//月结编码
                    $("#sShipper").val(data['sShipper']);//月结编码
                    $("#sMobile").val(data['sMobile']);//月结编码
                    $("#ProvinceID").val(data['province']);//月结编码
                    $("#CityID").val(data['city']);//月结编码
                    $("#AreaID").val(data['area']);//月结编码
                    $("#sAddress").val(data['sAddress']);//月结编码

                    $("#user").show();
                    $("#password").show();
                    $("#key").show();
                    $("#sendsite").show();
                    $("#expressCode").show();
                    var sExpressName = data['sExpressName'];
                    if(sExpressName==null||sExpressName==undefined||sExpressName==""){
                        $("#user").hide();
                    }

                    var expressPwd = data['expressPwd'];
                    if(expressPwd==null||expressPwd==undefined||expressPwd==""){
                        $("#password").hide();
                    }

                    var key = data['sExpressKey'];
                    if(key==null||key==undefined||key==""){
                        $("#key").hide();
                    }

                    var sendsite = data['sExpressSendSite'];
                    if(sendsite==null||sendsite==undefined||sendsite==""){
                        $("#sendsite").hide();
                    }
                    var ExpressCode = data['sExpressCode'];
                    if(ExpressCode==null||ExpressCode==undefined||ExpressCode==""){
                        $("#expressCode").hide();
                    }
                } else {
                    error(data.sMsg);
                }
            }

        })
    });

    $("#suredeliveryorder").click(function(){
        var templateID = $("#expressCompany").find("option:selected").val();
        var ids = $("#ids").val();
        $.ajax({
            type: "POST",
            url:"/shop/order/getdelivery",
            data:{
                ids:ids,
                templateID:templateID
            },
            beforeSend:function(){
                $("#suredeliveryorder").attr({ disabled: "disabled" });
            },
            success:function(data){
                if (data.bSuccess) {
                    openModal(data.data, 800, 400)
                } else {
                    error(data.sMsg);
                }
            }
        })
    })
</script>