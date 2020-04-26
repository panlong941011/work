<?php $this->beginBlock('style') ?>
    <link rel="stylesheet" href="/css/refundApply.css">
    <style>
        .vote-entry {
            clear: both;
            height: auto;
        }

        .basecontent .vote-entry {
            display: block;
            clear: both;
            height: 2rem;
            line-height: 2rem;
        }

        .vote-entry .control_conts {
            margin: 0;
            padding: 0;
            width: 80%;
        }

        .vote-entry .control_conts .w_txt {
            height: 1.5rem;
            line-height: 1.5rem;
            width: 100%;
            text-align: left;
            font-size: 0.65rem;
            margin: 0;
            padding: 0;
            display: block;
            border: 1px #ccc solid;
        }

        .basecontent {
            display: block;
            width: 96%;
            margin-left: 2%;
            padding: 5px;
            height: 10rem;
            background-color: #fff;
            margin-top: 1rem;
        }

        input[type="checkbox"] {
            -webkit-appearance: checkbox;
            height: 0.5rem;
            width: 0.5rem;
        }

        .wrapper label {
            font-size: 0.65rem;
        }

        textarea {
            height: 4rem;
            line-height: 0.65rem;
            width: 100%;
            text-align: left;
            font-size: 0.65rem;
            border: 1px #33AAAA solid;
        }

        .paginations button {
            display: block;
            background-color: #3394FF;
            height: 1.5rem;
            width: 80%;
            line-height: 0.65rem;
            font-size: 0.65rem;
            color: #fff;
            text-align: center;
            border: 0px none;
            outline: none;
            -webkit-background-clip: padding-box;
            -moz-background-clip: padding;
            border-radius: 3px;
            background-clip: padding-box;
            cursor: pointer;
            padding: 0;
            margin-left: 10%;

        }

        .paginations {
            width: 100%;
            margin: 0.5rem auto;
        }

        .question-6 .control_conts {
            width: 100%;
            clear: both;
        }


    </style>
<?php $this->endBlock() ?>
    <div class="refund_apply">
        <div class="ad_header">
            <a href="javascript:goBack();" class="ad_back">
                <span class="icon">&#xe885;</span>
            </a>
            <h2>品类设置</h2>
        </div>
        <div class="vote-details">
            <div class="vote-entry custom-richtext">
                <p style="margin-bottom:0px;margin-top:0px;line-height:0em;">
                    <img data-origin-height="450" src="http://product.aiyolian.cn/userfile/upload/20190725/8.jpg">
                </p>
            </div>

            <div class="basecontent">
                <div class="vote-entry question-1" data-type="text">
                    <div class="control_conts">
                        <input id="sName1" class="w_txt" type="text" value="<?= $arrCat[0]->sName ?>">
                    </div>
                </div>

                <div class="vote-entry question-2" data-type="text">
                    <div class="control_conts">
                        <input id="sName2" class="w_txt" type="text" value="<?= $arrCat[1]->sName ?>">
                    </div>
                </div>

                <div class="vote-entry question-3" data-type="text">
                    <div class="control_conts">
                        <input id="sName3" class="w_txt" type="text" value="<?= $arrCat[2]->sName ?>">
                    </div>
                </div>

                <div class="vote-entry question-4" data-type="text">
                    <div class="control_conts">
                        <input id="sName4" class="w_txt" type="text" value="<?= $arrCat[3]->sName ?>">
                    </div>
                </div>

                <div class="vote-entry question-4" data-type="text">
                    <div class="control_conts">
                        <input id="sName5" class="w_txt" type="text" value="<?= $arrCat[4]->sName ?>">
                    </div>
                </div>

            </div>


        </div>
        <div class="paginations clearfix first">
            <button class="submit" data-finished="finished" onclick="submitData()">提交
            </button>
        </div>
    </div>
<?php $this->beginBlock('foot') ?>
    <script>
        function submitData() {
            var sName1 = $('#sName1').val();
            var sName2 = $('#sName2').val();
            var sName3 = $('#sName3').val();
            var sName4 = $('#sName4').val();
            var sName5 = $('#sName5').val();
            if (sName1 == '' || sName2 == '' || sName3 == '' || sName4 == '' || sName5 == '') {
                alert("品类名称不能为空");
                return;
            }

            var formData = {
                sName1: sName1,
                sName2: sName2,
                sName3: sName3,
                sName4: sName4,
                sName5: sName5,
                _csrf: '<?=\Yii::$app->request->getCsrfToken()?>'
            };
            $.post('/supplier/setcat',
                formData, function (data) {
                    console.log(data);
                    if (data.status) {
                        window.location.reload();
                    } else {
                        alert('提交失败，请联系管理员');
                    }
                }, 'json');
        }
    </script>
<?php $this->endBlock() ?>