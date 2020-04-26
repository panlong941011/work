<style>
	.imgWrap {
		width: 90px;
		height: 90px;
		background: #E9EDEF;
		margin-top: 15px;
		position: relative;
	}
	.frameShow {
		position: absolute;
		left: 0;
		top: 0;
		color: #fff;
		width: 100%;
		height: 100%;
		text-align: center;
		line-height: 90px;
		font-size: 40px;
	}
	.choseImg {
		width: 90px;
		height: 26px;
		background: #32c5d2;
		text-align: center;
		line-height: 26px;
		font-size: 14px;
		color: #fff;
		position: relative;
		margin-top: 10px;
		margin-bottom: 5px;
	}
	.choseImg .upFile {
		position: absolute;
		left: 0;
		top: 0;
		width: 100%;
		height: 100%;
		opacity: 0;
		filter:alpha(opacity=0);
	}
	.headeImg {
		width: 100%;
		height: 100%;
		display: none;
		position: relative;
	}
	.headeImg img {
		width: 100%;
		height: 100%;
	}
	.delImg {
		width: 15px;
		height: 15px;
		background: #666;
		color: #fff;
		-webkit-border-radius: 100%!important;
		-moz-border-radius: 100%!important;
		border-radius: 100%!important;
		position: absolute;
		right: -5px;
		top: -5px;
		font-size: 14px;
		text-align: center;
		line-height: 15px;
		z-index: 10;
		cursor: pointer;
	}
	.upImgTip {
		font-size: 14px;
		color: #666;
		line-height: 1.4;
		margin: 0px !important;
	}
</style>

<div class="imgWrap">

    <? if ($sLinkFieldValue) { ?>
        <div class="frameShow" style="display: none">+</div>
        <div class="headeImg" style="display: block">
                <img src="<?=\Yii::$app->params['sUploadUrl']?>/<?=$sLinkFieldValue?>" alt="">
            <span class="delImg">×</span>
        </div>
    <? } else { ?>
        <div class="frameShow" onclick="$('.<?=$field->sFieldAs?>').click()">+</div>
        <div class="headeImg">
            <img src="" alt="">
            <span class="delImg">×</span>
        </div>
    <? } ?>

</div>
<div class="choseImg">
	选择
	<input type="file" class="upFile <?=$field->sFieldAs?>" accept="image/gif,image/jpeg,image/png">
    <input type="hidden" sFieldAs="<?=$field->sFieldAs?>" name="arrObjectData[<?=$field->sObjectName?>][<?=$field->sFieldAs?>]" value="<?=$data?>" />
    <input type="hidden" sFieldAs="<?=$field->sLinkField?>" name="arrObjectData[<?=$field->sObjectName?>][<?=$field->sLinkField?>]" value="<?=$sLinkFieldValue?>" />
    <input type="hidden" sFieldAs="<?=$field->sFieldAs?>Base64" name="arrObjectData[<?=$field->sObjectName?>][<?=$field->sFieldAs?>Base64]" value="" />
</div>
<script>
	$(function() {
		//上传图片
		$('.upFile').on('change',function(event) {
			var files = event.target.files || event.srcElement.files,
				file = files[0], 
				reader = new FileReader();
				console.log(file);

			if (files.length === 0) {
		      	return;
		    }
	     	if(file.type.indexOf('image') == -1){ // 判断是否是图片
                error('请上传正确的图片格式');
                return;
            } 

		   var maxSize = 2000000; //设置上传图片的质量 这里是3M
		   if( file.size > maxSize ) {
               error("上传图片大小不得大于2M");
		   		return;
		    }

		    reader.readAsDataURL(file);
		    reader.onload = function() {
		    	var result = this.result;  //后端要取的图片的值 base64的 要在这个函数里面赋值
		    	$('.headeImg img').attr('src',result);
		    	$('.frameShow').hide();
		    	$('.headeImg').show();
		    	$("input[sFieldAs='<?=$field->sFieldAs?>Base64']").val(result);
                $("input[sFieldAs='<?=$field->sFieldAs?>']").val('ttttt');
		    }
		});
		//删除图片
		$('.delImg').on('click',function() {
			$('.headeImg img').attr('src','');
			$('.frameShow').show();
		    $('.headeImg').hide();
		    $('.upFile').val('');
            $("input[sFieldAs='<?=$field->sFieldAs?>Base64']").val('');
            $("input[sFieldAs='<?=$field->sLinkField?>']").val('');
            $("input[sFieldAs='<?=$field->sFieldAs?>']").val('');
		})
	})
</script>