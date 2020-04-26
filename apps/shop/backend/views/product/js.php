function qrcode(id)
{
    $.get
    (
        '<?= Yii::$app->homeUrl ?>/<?= strtolower($this->context->sysObject->sObjectName) ?>/qrcode?id='+id,
        function(data)
        {
            var modal = openModal(data, 400, 380);


        }
    );


}

jQuery.getScript("/js/clipboard.min.js", function(data, status, jqxhr) {
    var clipboard2 = new Clipboard('#copy');
    clipboard2.on('success', function(e) {
        console.log(e);
        success("复制成功！")
    });

    clipboard2.on('error', function(e) {
        console.log(e);
        error("复制失败！请手动复制")
    });
});

