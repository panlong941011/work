<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <h4 class="modal-title"><?= Yii::t('app', '修改收货地址') ?>:</h4>
</div>
<div class="modal-body">
    <form name="modifyshipform">
        <div class="row">
            <div class="col-md-12">
                <div class="col-md-3">收货人：</div>
                <div class="col-md-8">
                    <input value="<?=$address->sName?>" class="form-control" name="name"></input>
                </div>
            </div>
            <div class="col-md-12 margin-top-20">
                <div class="col-md-3">手机号：</div>
                <div class="col-md-8">
                    <input value="<?=$address->sMobile?>" class="form-control" name="mobile"></input>
                </div>
            </div>
            <div class="col-md-12 margin-top-20">
                <div class="col-md-3">省：</div>
                <div class="col-md-8">
                    <select class="form-control" name="province" onchange="changeProvince()"></select>
                </div>
            </div>
            <div class="col-md-12 margin-top-20">
                <div class="col-md-3">市：</div>
                <div class="col-md-8">
                    <select class="form-control" name="city" onchange="changeCity(document.modifyshipform.province.value)"></select>
                </div>
            </div>
            <div class="col-md-12 margin-top-20">
                <div class="col-md-3">区：</div>
                <div class="col-md-8">
                    <select class="form-control" name="area"></select>
                </div>
            </div>
            <div class="col-md-12 margin-top-20">
                <div class="col-md-3">详细地址：</div>
                <div class="col-md-8">
                    <input value="<?=$address->sAddress?>" class="form-control" name="address"></input>
                </div>
            </div>
        </div>
    </form>
</div>
<div class="modal-footer">
    <button type="button" onclick="closeModal()" class="btn btn-outline dark"><?= Yii::t('app', '取消') ?></button>
    <button type="button" class="btn green" onclick="ok()"><?= Yii::t('app', '同意') ?></button>
</div>
<script>

    function ok() {
        $.post(
            '/shop/order/modifyaddress?ID=<?=$_GET['ID']?>',
            $(document.modifyshipform).serialize(),
            function (data) {
                if (data.status) {
                    success(data.message)
                    setInterval("location.reload();", 1000);
                } else {
                    error(data.message);
                }
            }
        )
    }


    function changeProvince() {
        $.get(
            "/shop/order/province?ID="+$(document.modifyshipform.province).val(),
            function (data) {
                $(document.modifyshipform.province).html(data);
                changeCity($(document.modifyshipform.province).val())
            }
        );
    }


    function changeCity(ProvinceID) {
        $.get(
            "/shop/order/city?ID="+$(document.modifyshipform.city).val()+"&UpID="+ProvinceID,
            function (data) {
                $(document.modifyshipform.city).html(data);
                changeArea($(document.modifyshipform.city).val())
            }
        );
    }

    function changeArea(CityID) {
        $.get(
            "/shop/order/area?ID=&UpID="+CityID,
            function (data) {
                $(document.modifyshipform.area).html(data);
            }
        );
    }


    $.get(
        "/shop/order/province?ID=<?=$address->ProvinceID?>",
        function (data) {
            $(document.modifyshipform.province).html(data);

            $.get(
                "/shop/order/city?ID=<?=$address->CityID?>&UpID=<?=$address->ProvinceID?>",
                function (data) {
                    $(document.modifyshipform.city).html(data);

                    $.get(
                        "/shop/order/area?ID=<?=$address->AreaID?>&UpID=<?=$address->CityID?>",
                        function (data) {
                            $(document.modifyshipform.area).html(data);
                        }
                    );
                }
            );
        }
    );

    clearToastr();
</script>