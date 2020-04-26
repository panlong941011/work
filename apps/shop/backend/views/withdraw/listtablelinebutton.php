<? if ($this->context->checkHasOperaPower('check')) { ?>
<td align="center" nowrap="nowrap">
    <? if ($data['CheckID']['ID'] == 0) { ?>
            <a href="javascript:;"
               onclick="check(<?=$data['lID']?>, this)">审核</a>
    <? } ?>
</td>
<? } ?>