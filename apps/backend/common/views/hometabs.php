<? if (count($arrList) == 1) { ?>
    <div id="listtable" class="margin-top-10 " style="margin-left: 6px; margin-right: 6px"
         listkey="<?= current($arrList)['sKey'] ?>" listid="<?= current($arrList)['ID'] ?>"></div>
<? } else { ?>
    <div class="tabbable-line tabbable tabbable-tabdrop">
        <ul class="nav nav-tabs">
            <? foreach ($arrList as $list) { ?>
                <li <? if ($_GET['sTabID'] == $list['ID'] || !$_GET['sTabID'] && $list['bDefault']) { ?>class="active"<? } ?>
                    listkey="<?= $list['sKey'] ?>">
                    <a href="#tab_<?= $list['ID'] ?>" data-id="<?= $list['ID'] ?>" data-toggle="tab"
                       data-slinkurl="<?= $list['sLinkUrl'] ?>"><?= $list['sName'] ?></a>
                </li>
            <? } ?>
        </ul>
        <div class="tab-content">
            <? foreach ($arrList as $list) { ?>
                <div class="tab-pane <? if ($_GET['sTabID'] == $list['ID'] || !$_GET['sTabID'] && $list['bDefault']) { ?>active<? } ?>"
                     listkey="<?= $list['sKey'] ?>" id="tab_<?= $list['ID'] ?>"></div>
            <? } ?>
        </div>
    </div>
<? } ?>