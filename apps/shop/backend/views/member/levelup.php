<?php
use myerm\modules\common\libs\SystemTime;

/**
 * @var \myerm\modules\laisanjin\models\SysUser $user
 * @var \myerm\modules\laisanjin\models\Agent $agents
 */
?>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <h4 class="modal-title"><?= Yii::t('app', '升级经销商') ?></h4>
</div>
<div class="modal-body">
    <form name="refundform" class="form-horizontal">
        <div class="form-group">
            <label class="col-md-2 control-label" style="width:120px">经销商类型:</label>
            <div class="col-md-2" style="width:120px">
                <select name="TypeID" class="form-control" style="width:150px" id="TypeID">
                    <? foreach ($agents as $agent) { ?>
                        <option value="<?= $agent->lID ?>"><?= $agent->sName ?></option>
                    <? } ?>
                </select>
            </div>
        </div>

        <div class="form-group special-refund">
            <label class="col-md-2 control-label" style="width:120px">推荐人ID:</label>
            <div class="col-md-2" style="width:120px">
                <input name="sRecommend" type="number" value="" class="form-control" id="sRecommend" style="width:150px">
            </div>
        </div>
</div>
</form>
</div>
<div class="modal-footer">
    <button type="button" onclick="closeModal()" class="btn btn-outline dark"><?= Yii::t('app', '取消') ?></button>
    <button type="button" class="btn green" onclick="ok()"><?= Yii::t('app', '确定') ?></button>
</div>
<script>
    function ok() {
        var sRecommend = $('#sRecommend').val();
        var TypeID = $('#TypeID').val();
        var url = sHomeUrl + '/' + sObjectName + '/levelup?ID=<?= $_GET['ID'] ?>';
        var parameter = {
            RecommendID: sRecommend,
            TypeID: TypeID,
        };

        info('正在提交。。。');

        $.post(url, parameter, function (data) {
            if (data.status) {
                success(data.msg);
                closeModal();
            } else {
                error(data.msg);
            }
        }, 'json');
    }
</script>