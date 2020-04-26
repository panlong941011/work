<? if ($field->bEnableRTE) { ?>
<script id="<?=$field->sObjectName?>/<?=$field->sFieldAs?>" name="arrObjectData[<?=$field->sObjectName?>][<?=$field->sFieldAs?>]" type="text/plain" style="width:100%;height:300px;"><?=$data?></script>
<script type="text/javascript">
$(document).ready
(
	function()
	{
		var ue = UE.getEditor('<?=$field->sObjectName?>/<?=$field->sFieldAs?>');
	}
);
</script>

<? } else { ?>
<textarea sDataType="<?=$field->sDataType?>" sFieldAs="<?=$field->sFieldAs?>" class="form-control" rows="10" name="arrObjectData[<?=$field->sObjectName?>][<?=$field->sFieldAs?>]"><?=$data?></textarea>
<? } ?>