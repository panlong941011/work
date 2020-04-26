function saveProfile()
{
	$(".has-error").removeClass('has-error');

	var bError = false;

	if ($.trim($("input[name='profile[sName]']").val()) == "") {
		$("input[name='profile[sName]']").closest('.form-group').addClass('has-error');
		bError = true;
	}
 
	if ($.trim($("input[name='profile[sEMail]']").val()) == "") {
		$("input[name='profile[sEMail]']").closest('.form-group').addClass('has-error');
		bError = true;
	} 
	
	if ($.trim($("input[name='profile[sMobile]']").val()) == "") {
		$("input[name='profile[sMobile]']").closest('.form-group').addClass('has-error');
		bError = true;
	}
	
	if ($.trim($("input[name='profile[sQQ]']").val()) == "") {
		$("input[name='profile[sQQ]']").closest('.form-group').addClass('has-error');
		bError = true;
	}	
	
	if (bError) {
		error('<?=\Yii::t('app', '红色部分为必填项')?>');
		return false;
	}
	
	$.post
	(
		'./saveprofile',
		$(document.profileform).serialize(),
		function(data)
		{
			var ret = jQuery.parseJSON(data);
			if (!ret.bSuccess) {
				error(ret.sMsg);
			} else {
				success(ret.sMsg);
				window.setTimeout('location.reload(true)', 1000);
			}
		}
	);
}

function savePassword()
{
	$(".has-error").removeClass('has-error');

	var bError = false;

	if ($.trim($("input[name='sCurrPass']").val()) == "") {
		$("input[name='sCurrPass']").closest('.form-group').addClass('has-error');
		bError = true;
	}

	if ($.trim($("input[name='sNewPass']").val()) == "") {
		$("input[name='sNewPass']").closest('.form-group').addClass('has-error');
		bError = true;
	}

	if ($.trim($("input[name='sNewPassConfirm']").val()) == "") {
		$("input[name='sNewPassConfirm']").closest('.form-group').addClass('has-error');
		bError = true;
	}
	
	if (bError) {
		error('<?=\Yii::t('app', '红色部分为必填项')?>');
		return false;
	}
	
	if ($.trim($("input[name='sNewPass']").val()) != $.trim($("input[name='sNewPassConfirm']").val())) {
	
		$("input[name='sNewPass']").closest('.form-group').addClass('has-error');
		$("input[name='sNewPassConfirm']").closest('.form-group').addClass('has-error');
	
		error('<?=\Yii::t('app', '两次密码输入不一致')?>');
		return false;	
	}
	
	$.post
	(
		'./savepassword',
		$(document.passwordform).serialize(),
		function(data)
		{
			var ret = jQuery.parseJSON(data);
			if (!ret.bSuccess) {
				error(ret.sMsg);
			} else {
				success(ret.sMsg);
				window.setTimeout('location.reload(true)', 1000);
			}
		}
	);
}