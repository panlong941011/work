<style>
    .common_ui_module {
        width: 100%;
        float: left;
        margin-top: 4px 0;
    }

    .common_ui_module span:first-child {
        color: red;
    }

    .common_ui_module span {
        float: left;
        margin-right: 5px;
    }

    .submitButton {
        text-align: center;
    }

    .button {
        height: 35px;
        border: 0;
        background: #32C5D2;
        color: #fff;
        cursor: pointer;
        margin-right: 5px
    }

    .submitButton button {
        width: 160px;
        height: 35px;
        border: 0;
        background: #32C5D2;
        color: #fff;
        cursor: pointer;
        margin-top: 10px;
    }

    .express_type div {
        float: left;
    }

    .express_type input {
        margin: 0 5px 0 10px;
    }

    .orderDeliver {
        table-layout: fixed;
        text-align: center;
    }

    .orderDeliver td {
        vertical-align: middle !important;
    }

    .disableBtn {
        background: #e1e5ec !important;
    }
</style>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <h4 class="modal-title">拆分订单物流</h4>
</div>

<form name="SeparateForm" class="form-horizontal" onsubmit="return false;">
    <input type="hidden" name="_csrf" value="<?= \Yii::$app->request->getCsrfToken() ?>">
    <input type="hidden" name="OrderID" value="<?= $OrderDetail[0]['OrderID'] ?>">
    <!--原始订单数据-->
    <div class="modal-body">
        <table class="table table-bordered table-hover orderDeliver"
                id="OrderDetail">
            <tr role="row">
                <td style="display: none">商品ID</td>
                <td width="150px">商品</td>
                <td>规格</td>
                <td style="display: none">关键字</td>
                <td>数量</td>
                <td>剩余未拆分数量</td>
                <td>拆分数量</td>
            </tr>
			<? foreach ($OrderDetail as $DetailKey => $OrderDetail) { ?>
                <tr>
                    <td style="display: none"><?= $OrderDetail['ProductID'] ?></td>
                    <td><a href="javascript:;"
                                onclick="parent.addTab($(this).text(), '/shop/product/view?ID=<?= $OrderDetail['ProductID'] ?>')">
							<?= $OrderDetail['sName'] ?></a>
                    </td>
                    <td><?= $OrderDetail['sSKU'] ?></td>
                    <td style="display: none"><?= $OrderDetail['sKeyword'] ?></td>
                    <td><?= $OrderDetail['lQuantity'] ?></td>
                    <td mark="<?= $OrderDetail['ProductID'] ?>;<?= $OrderDetail['sSKU'] ?>"></td>
                    <td><input type="text"
                                class="form-control"
                                value="0"
                                name=""></td>
                </tr>
			<? } ?>
            <tr>
                <td colspan="5" style="text-align: right;">
                    <button class="button"
                            onclick="AddExpress()">添加订单物流
                    </button>
                </td>
            </tr>
        </table>
    </div>

    <!--已拆的订单物流数据-->
    <div class="modal-footer">
        <!--提示信息-->
        <div id="prompt" class="alert alert-danger <? if ($sProductInfo) { ?>hide<? } ?>" style="text-align: left">
            存在商品未进行拆分
        </div>
        <div class="common_ui_module">
            <table class="table table-bordered orderDeliver
                  <? if (!$sProductInfo) { ?>hide<? } ?>"
                    id="SeparateExpress">
                <tr role="row">
                    <td>编号</td>
                    <td style="display: none">商品ID</td>
                    <td width="150px">商品</td>
                    <td style="display: none">关键字</td>
                    <td>规格</td>
                    <td>数量</td>
                    <td>操作</td>
                </tr>
				<? if ($sProductInfo) { ?>
					<? foreach ($sProductInfo as $sProductKey => $sProductValue) { ?>
						<? foreach ($sProductValue as $k => $v) { ?>
                            <tr class='No<?= $sProductKey + 1 ?>' name='OrderExpress'>
								<? if ($k == 0) { ?>
                                    <td class='FirstTd<?= $sProductKey + 1 ?>'
                                            name='SeparateNo'
                                            rowspan="<?= count($sProductValue) ?>"
                                            style="vertical-align: middle">
										<?= $sProductKey + 1 ?>
                                    </td>
								<? } ?>
                                <td style="display: none"><?= $v['ProductID'] ?></td>
                                <input type='hidden' class="ProductID" name='ProductID[<?= $sProductKey + 1 ?>][]' value='<?= $v['ProductID'] ?>'>
                                <td><?= $v['sName'] ?></td>
                                <input type='hidden' class="sProductName" name='sProductName[<?= $sProductKey + 1 ?>][]' value='<?= $v['sName'] ?>'>
                                <td><?= $v['sSKU'] ?></td>
                                <input type='hidden' class="sSKU" name='sSKU[<?= $sProductKey + 1 ?>][]' value='<?= $v['sSKU'] ?>'>
                                <td style="display: none"><?= $v['sKeyword'] ?></td>
                                <input type='hidden' class="sKeyword" name='sKeyword[<?= $sProductKey + 1 ?>][]' value='<?= $v['sKeyword'] ?>'>
                                <td mark='<?= $v['ProductID'] ?>;<?= $v['sSKU'] ?>' separateno='<?= $sProductKey + 1 ?>'><?= $v['lQuantity'] ?></td>
                                <input type='hidden' class="lQuantity" name='lQuantity[<?= $sProductKey + 1 ?>][]' value='<?= $v['lQuantity'] ?>'>
								<? if ($k == 0) { ?>
                                    <td class='FirstTd<?= $sProductKey + 1 ?>'
                                            name='SeparateOperating'
                                            rowspan="<?= count($sProductValue) ?>"
                                            style="vertical-align: middle">
                                        <i class='fa fa-minus-square' separateno="<?= $sProductKey + 1 ?>" onclick=removeSeparate(this) title='删除订单物流'></i>
                                    </td>
								<? } ?>
                            </tr>
						<? } ?>
					<? } ?>
				<? } ?>
            </table>
        </div>
        <div class="submitButton">
            <button class="disableBtn" disabled onclick="deliver()">提 交</button>
        </div>
    </div>
</form>
<script>
    //编号
	<? if ($sProductInfo) { ?>
    var number = <?= count($sProductInfo)?>;
	<? } else { ?>
    var number = 1;
	<? } ?>


    /**
     * 添加订单物流
     * @author hechengcheng
     * @time 2018年7月10日16:22:08
     */
    function AddExpress() {
        //拼接td的记数
        var SeparateTdNum = 1;

        //拆分数量为0的记数
        var SeparateNum = 0;

        //剩余数量为0的商品数
        var SurplusNum = 0;

        var mytable = document.getElementById("OrderDetail");
        //验证部分
        for (var j = 1; j < mytable.rows.length - 1; j++) {
            //获取拆分数量
            var lQuantity = Number(mytable.rows[j].cells[6].getElementsByTagName("input")[0].value);
            //获取剩余数量
            var lSurplus = Number(mytable.rows[j].cells[5].innerText);

            if (lQuantity > lSurplus) {
                error("拆分数量大于剩余数量");
                return false;
            }

            if (lSurplus - lQuantity == 0) {
                SurplusNum++;
            }

            //判断是否拆分完毕
            if (j == mytable.rows.length - 2 && SurplusNum == mytable.rows.length - 2) {
                $("#prompt").addClass('hide');
                $(".submitButton button").removeClass('disableBtn');
                $('.submitButton button').removeAttr("disabled");
            }

            //验证是否有输入数字
            if (lQuantity == 0) {
                SeparateNum++;
                if (SeparateNum == mytable.rows.length - 2) {
                    error("请输入拆分数量");
                    return false;
                }
                continue;
            }

            //验证是否输入有效数字
            if (!/(^[1-9]\d*$)/.test(lQuantity)) {
                error("请输入有效数字");
                return false;
            }
        }

        //拆单部分
        for (var i = 1; i < mytable.rows.length - 1; i++) {
            var str = "";

            //获取拆分数量
            var lQuantity = Number(mytable.rows[i].cells[6].getElementsByTagName("input")[0].value);

            if (lQuantity == 0) {
                continue;
            }

            //修改剩余数量
            mytable.rows[i].cells[5].innerText -= lQuantity;

            //获取商品ID
            var ProductID = mytable.rows[i].cells[0].innerText;

            //获取商品名
            var sProductName = mytable.rows[i].cells[1].innerText;

            //获取商品规格
            var sSKU = mytable.rows[i].cells[2].innerText;

            //获取商品关键字
            var sKeyword = mytable.rows[i].cells[3].innerText;

            str += "<tr class='No" + number + "' name='OrderExpress'>";
            if (SeparateTdNum == 1) {
                str += "<td class='FirstTd" + number + "' name='SeparateNo'>" + number + "</td>";
            }
            str += "<td style='display:none'>" + ProductID + "</td>";
            str += "<input type='hidden' class='ProductID' name='ProductID[" + number + "][]' value='" + ProductID + "'>";
            str += "<td>" + sProductName + "</td>";
            str += "<input type='hidden' class='sProductName' name='sProductName[" + number + "][]' value='" + sProductName + "'>";
            str += "<td>" + sSKU + "</td>";
            str += "<input type='hidden' class='sSKU' name='sSKU[" + number + "][]' value='" + sSKU + "'>";
            str += "<td style='display: none'>" + sKeyword + "</td>";
            str += "<input type='hidden' class='sKeyword' name='sKeyword[" + number + "][]' value='" + sKeyword + "'>";
            str += "<td mark='" + ProductID + ";" + sSKU + "' separateno='" + number + "'>" + lQuantity + "</td>";
            str += "<input type='hidden' class='lQuantity' name='lQuantity[" + number + "][]' value='" + lQuantity + "'>";
            if (SeparateTdNum == 1) {
                str += "<td class='FirstTd" + number + "' name='SeparateOperating'>";
                str += "<i class='fa fa-minus-square' separateno='" + number + "' onclick=removeSeparate(this) title='删除订单物流'></i></td>";
            }
            str += "</tr>";

            $(".FirstTd" + number).attr('rowspan', SeparateTdNum);
            $(".FirstTd" + number).css("vertical-align", "middle");
            SeparateTdNum++;

            $("#SeparateExpress").removeClass('hide').append(str);

            mytable.rows[i].cells[6].getElementsByTagName("input")[0].value = 0;
        }

        number++;
    }

    /**
     * 获取未拆分数量
     * @author hechengcheng
     * @time 2018年7月10日10:22:34
     */
    $(function () {
        var mytable = document.getElementById("OrderDetail");
		<? if ($sProductInfo) { ?>
        for (var i = 1; i < mytable.rows.length - 1; i++) {
            mytable.rows[i].cells[5].innerText = 0;
        }
		<? } else { ?>
        for (var i = 1; i < mytable.rows.length - 1; i++) {
            mytable.rows[i].cells[5].innerText = mytable.rows[i].cells[4].innerText;
        }
		<? } ?>
    });

    /**
     * 删除订单物流
     * @author hechengcheng
     * @time 2018年7月11日10:22:34
     */
    function removeSeparate(obj) {
        //加回未拆分数量
        var markNo = $(obj).attr("separateno");
        $("td[separateno='" + markNo + "']").each(function () {
            var mark = $(this).attr("mark");
            var OldQuantity = Number($("#OrderDetail td[mark='" + mark + "']").text());
            var ChangeQuantity = Number($(this).text());
            $("#OrderDetail td[mark='" + mark + "']").text(OldQuantity + ChangeQuantity)
        });

        //删除对应订单物流
        var removeClass = $(obj).parent().parent().attr("class");
        $("." + removeClass).remove();

        $("#prompt").removeClass('hide');
        $(".submitButton button").addClass('disableBtn');
        $('.submitButton button').attr("disabled", "true");
        if ($("tr[name='OrderExpress']").size() == 0) {
            $("#SeparateExpress").addClass('hide');
        }

        //重新赋值编号
        number = 0;
        $("tr[name='OrderExpress']").each(function(){
            var SeparateNo = $(this).find($("td[name='SeparateNo']"));
            var SeparateOperating = $(this).find($("td[name='SeparateOperating']"));

            if (SeparateNo.length > 0) {
                number++;
                SeparateNo.text(number);
                SeparateNo.attr('class', 'FirstTd' + number);
                SeparateOperating.attr('class', 'FirstTd' + number);
            }
            
            $(this).attr('class', 'No' + number);
            $(this).find("input[class='ProductID']").attr('name', 'ProductID[' + number + '][]');
            $(this).find("input[class='sProductName']").attr('name', 'sProductName[' + number + '][]');
            $(this).find("input[class='sSKU']").attr('name', 'sSKU[' + number + '][]');
            $(this).find("input[class='sKeyword']").attr('name', 'sKeyword[' + number + '][]');
            $(this).find("input[class='lQuantity']").attr('name', 'lQuantity[' + number + '][]');
        });
        
        number ++;
    }

    /**
     * 提交订单物流
     * @author hechengcheng
     * @time 2018年7月11日15:22:34
     */
    function deliver() {
        //提交部分
        info("正在提交修改，请稍等。。。");
        $.post('/shop/orderlogistics/separateexpress', $(document.SeparateForm).serialize(), function (data) {
            if (data.status) {
                success(data.msg);
                closeModal();
                reload();
            } else {
                error(data.msg);
            }
        }, 'json');
    }
</script>