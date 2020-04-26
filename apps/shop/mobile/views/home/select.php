<link rel="stylesheet" href="/css/address.css?1">
<link rel="stylesheet" href="/css/ydui.css">
<style>
    .m-cityselect {
        top: 0;
        height: 92%;
    }
    footer {
        position: fixed;
        left: 0;
        bottom: 0;
        width: 100%;
        z-index: 100;
    }
    .buy_now {
        width: 100%;
        height: 2rem;
        line-height: 2rem;
        text-align: center;
        color: #fff;
        background: #f42323;
        font-size: 0.8rem;
    }
</style>
<div class="m-cityselect" style="display: block;">
    <div class="cityselect-header"><p class="cityselect-title">附近门店</p>
    </div>
    <ul class="cityselect-content">
        <li class="cityselect-item">
            <div class="cityselect-item-box">
                <a class="crt" href="javascript:;"><span>黄则和</span></a>
            </div>
        </li>
        <li class="cityselect-item">
            <div class="cityselect-item-box">
                <? foreach ($arrGroupaddress as $address) { ?>
                    <a class="" href="https://yl.aiyolian.cn/shop<?=$address->MemberID?>/home"><span><?= $address->sName ?></span></a>
                <? } ?>
            </div>
        </li>
    </ul>
</div>
<footer>
    <div class="fix_btn flex">
        <a id="buy_now" style="width: 100%;" class="buy_now " href="/home/groupapply">
            团长申请
        </a>
    </div>
</footer>