<? if ($this->context->checkHasOpera('edit') || $this->context->checkHasOpera('clone')) { ?>
    <td align="center" nowrap="nowrap">
        <? if ($this->context->checkHasOpera('edit')) { ?>
            <a href="javascript:;"
               onclick="parent.addTab('<?= htmlspecialchars($data[$this->context->sysObject->sNameField]) ?>', '<?= Yii::$app->homeUrl ?>/<?= strtolower($this->context->sysObject->sObjectName) ?>/edit?ID=<?= $data[$this->context->sysObject->sIDField] ?>')">编辑</a>
        <? } ?>
        <? if ($this->context->checkHasOpera('clone')) { ?>
            <a href="javascript:;"
               onclick="parent.addTab('复制<?= addslashes($data[$this->context->sysObject->sNameField]) ?>', '<?= Yii::$app->homeUrl ?>/<?= strtolower($this->context->sysObject->sObjectName) ?>/clone?ID=<?= $data[$this->context->sysObject->sIDField] ?>')">复制</a>
        <? } ?>
    </td>
<? } ?>