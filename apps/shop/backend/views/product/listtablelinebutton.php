<td align="center" nowrap="nowrap">
    <? if ($this->context->checkHasOpera('edit')) { ?>
        <a href="javascript:;"
           onclick="parent.addTab('<?= htmlspecialchars($data[$this->context->sysObject->sNameField]) ?>', '<?= Yii::$app->homeUrl ?>/<?= strtolower($this->context->sysObject->sObjectName) ?>/edit?ID=<?= $data[$this->context->sysObject->sIDField] ?>')">编辑</a>
    <? } ?>
</td>
