<? if ($this->context->checkHasOpera('edit') || $this->context->checkHasOpera('clone')) { ?>
    <td align="center" nowrap="nowrap">
        <? if ($this->context->checkHasOpera('edit')) { ?>
            <a href="javascript:;"
               onclick="parent.addTab('<?= htmlspecialchars($data[$this->context->sysObject->sNameField]) ?>', '<?= Yii::$app->homeUrl ?>/<?= strtolower($this->context->sysObject->sObjectName) ?>/edit?ID=<?= $data[$this->context->sysObject->sIDField] ?>')">编辑</a>
        <? } ?>
        <? if ($this->context->checkHasOpera('recharge')) { ?>
            <a href="javascript:;"
               onclick="recharge('<?=$data['lID']?>')">充金币</a>
        <? } ?>
    </td>
<? } ?>