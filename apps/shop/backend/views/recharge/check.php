<style>
    .checkbox{
        margin-left:130px;
        font-size: 14px;
    }
    .reason{
        display: none;
        margin-top:10px;
        margin-bottom:10px
    }
    span{
        font-size:14px;
        margin-left:75px;
    }
</style>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <h4 class="modal-title">渠道款充值审核</h4>
</div>
<div class="checkbox">
    <label>
        <input type="radio" name="check" checked onclick="hide_reason()" value="1"> 同意
    </label>
</div>
<div class="checkbox">
    <label>
        <input type="radio" name="check" onclick="show_reason()" value="-1"> 驳回
    </label>
</div>
<div id="reason" class="reason">
    <span>驳回原因：</span><input type="text" id="fail_reason" placeholder="请输入驳回原因">
</div>
<div class="form-group text-center">
    <button class="cancel btn btn-default" onclick="close_modal()">取消</button>
    <button class="confirm btn btn-success" onclick="submit_button(<?=$ID?>)">确定</button>
</div>
