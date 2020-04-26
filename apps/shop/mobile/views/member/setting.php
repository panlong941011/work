<?php $this->beginBlock('style') ?>
<link rel="stylesheet" href="/css/member.css?<?= \Yii::$app->request->sRandomKey ?>">
<?php $this->endBlock() ?>
<div class="member_set">
    <div class="change_pwd">
        <a href="/member/modifyshopname" class="change_link">店铺名称</a>
    </div>
    <div class="change_pwd">
        <a href="/member/modifylogo" class="change_link">品牌LOGO</a>
    </div>
    <div class="change_pwd">
        <a href="/member/modifybanner" class="change_link">轮播图</a>
    </div>
    <div class="change_pwd">
        <a href="/member/modifymsg" class="change_link">店长说</a>
    </div>
    <div class="change_pwd">
        <a href="/member/modifyqrcode" class="change_link">店铺二维码</a>
    </div>
    <div class="change_pwd">
        <a href="/member/modifywxqrcode" class="change_link">个人微信二维码</a>
    </div>
    <div class="change_pwd">
        <a href="/member/modifymobile" class="change_link">客户电话</a>
    </div>
    <div class="change_pwd">
        <a href="/member/modifycommsg" class="change_link">更改公司简介</a>
    </div>
</div>
<?= $this->render('/layouts/foot', ['bIndex' => true]) ?>