function getField()
{
	var sObjectName = document.objectform.sObjectName.value;

	$.get
	(
		'<?=Yii::$app->homeUrl?>/system/import/getfield?sObjectName='+sObjectName,
		function(data)
		{
			$("select[name='sKeyField[]']").html(data);
		}
	);
	
}

function addKeyField()
{
	var clone = $("select[name='sKeyField[]']:first").clone(true);
	$("#keyfield").append("<select name=\"sKeyField[]\" class=\"form-control  margin-top-10\">"+$(clone).html()+"</select>");
}

function delKeyField()
{
	if ($("select[name='sKeyField[]']").size() > 1) {
		$("select[name='sKeyField[]']:last").remove();
	}
}

function checkValidate(theform)
{
	if($.trim(document.objectform.file.value) == "")
	{
		parent.tip("error", "请选择要上传的Excel文件。");
		return false;
	}
	
	var ext = document.objectform.file.value.substr(document.objectform.file.value.lastIndexOf('.')+1).toLowerCase();
	
	if(ext != 'xls' && ext != 'xlsx')
	{
		error("请选择扩展名为xls或者xlsx的文件。");
		return false;
	}
	
	var sObjectName = document.objectform.sObjectName.value;
	
	document.objectform.action = '<?=Yii::$app->homeUrl?>/system/import/save?sObjectName='+sObjectName;
	document.objectform.submit();
}


getField()