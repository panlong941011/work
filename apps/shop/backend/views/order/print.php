<style>
    th{
        width: 14%;
        text-align: center;
    }
    td{
        text-align: center;
    }
</style>
<style id="style_print">
    *,table,div,tr,td {
        margin: 0;
        padding: 0;
    }
    .print_paper{font-size: 15px;border: none;border-collapse: collapse;width: 374px; margin-left: 1px;table-layout: fixed;}

    .print_paper td {border: solid #000 1px;overflow: hidden;}

    .table_first {margin-top: 0;}

    .no_border td {border: none;vertical-align: top;}


    .f20 {font-size: 20pt;}

    .f16 {font-size: 16pt;}

    .f10 {font-size: 10pt;}

    .f9 {font-size: 9pt;}

    .f8 {font-size: 7pt;}

    .f6 {font-size: 5pt;}

    .f5 {font-size: 5pt;}

    .b {font-weight: bold;}

    .tc {text-align: center;}

    .vt {vertical-align: top;}
    .print_paper .bln {border-left: none;}

    .print_paper .brn {border-right: none;}

    .print_paper .bbn {border-bottom: none;}

    .l {float: left;}

    .ohh {white-space: nowrap;overflow: hidden;}

    .w1 {width: 100%;}

    .pp-bn td {border: none;}

    .t-bn td {border: none;}

    .pl5 {padding-left: 5px;}

    .pl80 {padding-left: 80px;}

</style>
<div class="modal-header">
    <h4 class="modal-title printTitle">打印电子面单</h4>
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <input type="text" value="<?=$companyName?>" id="companyName" style="display: none">
</div>
<div class="modal-body" style="overflow: auto; height: 420px;">
    <table class="deliveryTable" border="1" cellspacing="0" cellpadding="0" style="display: block; width: 100%">
        <thead>
        <tr>
            <th>子订单号</th>
            <th>快递单号</th>
            <th>物流公司</th>
        </tr>
        </thead>

        <tbody class="deliveryTbody">
        <?php foreach ($data as $v):?>
            <?php foreach ($v as $orderLogisticsInfo):?>
            <tr>
                <td><?=$orderLogisticsInfo['sName']?></td>
                <td><?=$orderLogisticsInfo['sExpressNo']?></td>
                <td><?=$orderLogisticsInfo['sExpressCompany']?></td>
            </tr>
            <?php endforeach;?>
        <?php endforeach;?>
        </tbody>
    </table>
    <div id="content" style="display: none">
        <?=$html?>
    </div>

</div>
    <div class="modal-footer">
        <button type="button" class="btn green" onclick="ok()"><?=Yii::t('app', '确定')?></button>
        <button type="button" onclick="closeModal()" class="btn btn-outline dark">取消</button>
    </div>
<script language="javascript" src="/js/LodopFuncs.js"></script>
<object id="LODOP_OB" classid="clsid:2105C259-1E0C-4534-8141-A753534CB4CA" width=0 height=0>
    <embed id="LODOP_EM" type="application/x-print-lodop" width=0 height=0 pluginspage="install_lodop32.exe"></embed>
</object>
<script>
    //确定补打
    function ok() {
        var IDs  = <?=$ids?>;
        $.ajax({
            url:'/shop/order/changestatus',
            type: 'POST',
            data:{
                id:IDs
            },
            success:function(){

            }
        });

        var companyName = $("#companyName").val();
        var content = $("#content").html();
        var expressOrderInfo = <?=$expressOrderInfoJson?>;
        previewLoadp(companyName,content,expressOrderInfo);
    }

    /**
     * 打印预览
     * @param title 文件命名
     * @param content 打印内容
     */
    function previewLoadp(expressName,content,expressOrderInfo,width="",height="") {
        var width= width;
        var height = height;
        if(width==null||width==undefined||width==""){
            width=1000;
            switch (expressName){
                case 'SF':
                case 'EMS':
                case '信丰':
                case '中铁':
                    height = 1500;
                    break;
                case 'YTO':
                case 'STO':
                case '全峰':
                case '天天':
                case 'ZTO':
                case '安能':
                case '优速':
                    height = 1775;
                    break;
                case 'YZPY':
                    height = 1800;
                    break;
                case 'YD':
                    height = 1775;
                    break;
                case '德邦':
                    height = 1770;
                    break;
                case '龙邦':
                    width = 6000;
                    height = 4000;
                    break;
                case '宅急送':
                    height = 1160;
                    break;
                case 'JD':
                    height = 1200;
                    break;
                case 'HTKY':
                    height = 1775;
                    break;
                default:
                    break;
            }
        }

        var LODOP=getLodop();
        LODOP.PRINT_INIT('快递');
        if(expressName == 'JD'){
            var top1=6.2;var top2=80.2;
            var heighty=120.12;
            var strBodyStyle = "<style>" + document.getElementById("style_print").innerHTML + "</style>";
            var strFormHtml = strBodyStyle + "<body>" + content + "</body>";
            LODOP.SET_PRINT_PAGESIZE(0,width,height,"Sheet");
            LODOP.SET_PRINT_STYLE("Bold",1);
            LODOP.ADD_PRINT_HTM(0,0,"100%","100%",strFormHtml);
            for(var i=0;i<expressOrderInfo.length;i++){//循环条形码和运单号码
                LODOP.ADD_PRINT_BARCODE(top1+'mm', 20, 350, 65, "128A",expressOrderInfo[i]['LogisticCode']+'-1-1-');
                LODOP.SET_PRINT_STYLEA(0,"AlignJustify",2);
                LODOP.ADD_PRINT_BARCODE(top2+'mm', 15, 200, 40, "128A",expressOrderInfo[i]['LogisticCode']);
                LODOP.SET_PRINT_STYLEA(0,"AlignJustify",2);
                top1+=heighty;
                top2+=heighty;
            }
        }

        else if(expressName =='YD'){//韵达
            var top1=75;
            var top2=116,
                top3=28.2;
            var heighty=177;
            LODOP.SET_PRINT_PAGESIZE(0,width,height,"Sheet");
            var strBodyStylebs = "<style>*,div,table,tr,td {margin: 0;padding: 0;}</style>";
            var strFormHtmlbs = strBodyStylebs  + content ;
            LODOP.ADD_PRINT_HTM(0,0,"100%","100%",strFormHtmlbs);
            LODOP.SET_PRINT_STYLE("Bold",1);

            for(var i=0;i<expressOrderInfo.length;i++){//循环条形码和运单号码
                LODOP.ADD_PRINT_BARCODE(top3+'mm',250,150,25,"Code39",expressOrderInfo[i]['PackageCode']);
                LODOP.SET_PRINT_STYLEA(0,"ShowBarText",0);

                LODOP.ADD_PRINT_BARCODE(top1+'mm', 30, 340, 50, "128auto", expressOrderInfo[i]['LogisticCode']);
                LODOP.SET_PRINT_STYLEA(0,"AlignJustify",2);
                LODOP.ADD_PRINT_BARCODE(top2+'mm', 175, 180, 30, "128auto",expressOrderInfo[i]['LogisticCode']);
                LODOP.SET_PRINT_STYLEA(0,"AlignJustify",2);
                top1+=heighty;
                top2+=heighty;
                top3+=heighty;
            }
        }
        else if(expressName  == 'YTO'){
            var top1=43;var top2=115;
            var heighty=177;
            LODOP.SET_PRINT_PAGESIZE(0,width,height,"Sheet");
            var strBodyStyleyto = "<style>*,div,table,tr,td {margin: 0;padding: 0;}</style>";
            var strFormHtmlyto = strBodyStyleyto  + content ;
            LODOP.ADD_PRINT_HTM(0,0,"100%","100%",strFormHtmlyto);
            LODOP.SET_PRINT_STYLE("Bold",1);

            for(var i=0;i<expressOrderInfo.length;i++){//循环条形码和运单号码
                LODOP.ADD_PRINT_BARCODE(top1+'mm', 30, 340, 50, "128Auto", expressOrderInfo[i]['LogisticCode']);
                LODOP.SET_PRINT_STYLEA(0,"AlignJustify",2);
                LODOP.ADD_PRINT_BARCODE(top2+'mm', 175, 180, 30, "128Auto", expressOrderInfo[i]['LogisticCode']);
                LODOP.SET_PRINT_STYLEA(0,"AlignJustify",2);
                top1+=heighty;
                top2+=heighty;
            }
        }

        else if(expressName=='HTKY'){//百世
            var top1=75;
            var top2=114.5;
            var heighty=177.5;
            LODOP.SET_PRINT_PAGESIZE(0,width,height,"Sheet");
            var strBodyStylebs = "<style>*,div,table,tr,td {margin: 0;padding: 0;}</style>";
            var strFormHtmlbs = strBodyStylebs  + content ;
            LODOP.ADD_PRINT_HTM(0,0,"100%","100%",strFormHtmlbs);
            LODOP.SET_PRINT_STYLE("Bold",1);

            for(var i=0;i<expressOrderInfo.length;i++){//循环条形码和运单号码
                LODOP.ADD_PRINT_BARCODE(top1+'mm', 30, 340, 50, "128c",expressOrderInfo[i]['LogisticCode']);
                LODOP.SET_PRINT_STYLEA(0,"AlignJustify",2);
                LODOP.ADD_PRINT_BARCODE(top2+'mm', 175, 180, 30, "128c",expressOrderInfo[i]['LogisticCode']);
                LODOP.SET_PRINT_STYLEA(0,"AlignJustify",2);
                top1+=heighty;
                top2+=heighty;
            }
        }
        else if(expressName=='STO'){//申通
            var top1=75;var top2=114.1;var top3=26.2;
            var heighty=177;
            LODOP.SET_PRINT_PAGESIZE(0,width,height,"Sheet");
            var strBodyStylest = "<style>*,div,table,tr,td {margin: 0;padding: 0;}</style>";
            var strFormHtmlst = strBodyStylest  + content ;
            LODOP.ADD_PRINT_HTM(0,0,"100%","100%",strFormHtmlst);
            LODOP.SET_PRINT_STYLE("Bold",1);

            for(var i=0;i<expressOrderInfo.length;i++){//循环条形码和运单号码
                LODOP.ADD_PRINT_BARCODE(top1+'mm', 30, 340, 50, "128c",expressOrderInfo[i]['LogisticCode']);
                LODOP.SET_PRINT_STYLEA(0,"AlignJustify",2);
                LODOP.ADD_PRINT_BARCODE(top2+'mm', 175, 180, 30, "128c", expressOrderInfo[i]['LogisticCode']);
                LODOP.SET_PRINT_STYLEA(0,"AlignJustify",2);
                LODOP.ADD_PRINT_BARCODE(top3+'mm',290,80,29,"128c",expressOrderInfo[i]['LogisticCode']);
                LODOP.SET_PRINT_STYLEA(0,"ShowBarText",0);
                top1+=heighty;
                top2+=heighty;
                top3+=heighty;
            }
        }

        else if(expressName=='SF'){
            var top1=25;var top2=89.9;
            var heighty=150.02;
            LODOP.SET_PRINT_PAGESIZE(0,width,height,"Sheet");
            var strBodyStylest = "<style>*,div,table,tr,td {margin: 0;padding: 0;}</style>";
            var strFormHtmlst = strBodyStylest  + content ;
            LODOP.ADD_PRINT_HTM(0,0,"100%","100%",strFormHtmlst);
            LODOP.SET_PRINT_STYLE("Bold",1);

            for(var i=0;i<expressOrderInfo.length;i++){//循环条形码和运单号码
                LODOP.ADD_PRINT_BARCODE(top1+'mm', 15,220, 50, "128c",expressOrderInfo[i]['LogisticCode']);
                LODOP.SET_PRINT_STYLEA(0,"AlignJustify",2);
                LODOP.ADD_PRINT_BARCODE(top2+'mm', 185, 190, 30, "128c",expressOrderInfo[i]['LogisticCode']);
                LODOP.SET_PRINT_STYLEA(0,"AlignJustify",2);
                top1+=heighty;
                top2+=heighty;
            }
        }
        else if(expressName=='YZPY'){
            var top1=76;
            var top2=114.1;
            var heighty=179.91;
            var strBodyStylest = "<style>*,div,table,tr,td {margin: 0;padding: 0;}</style>";
            strFormHtml = strBodyStylest  + content ;
            LODOP.SET_PRINT_PAGESIZE(0,width,height,"Sheet");
            LODOP.ADD_PRINT_HTM(0,0,"100%","100%",strFormHtml);
            LODOP.SET_PRINT_STYLE("Bold",1);
            
            for(var i=0;i<expressOrderInfo.length;i++){//循环条形码和运单号码
                LODOP.ADD_PRINT_BARCODE(top1+'mm', 30, 340, 50, "128auto", expressOrderInfo[i]['LogisticCode']);
                LODOP.SET_PRINT_STYLEA(0,"AlignJustify",2);
                LODOP.ADD_PRINT_BARCODE(top2+'mm', 175, 180, 30, "128auto", expressOrderInfo[i]['LogisticCode']);
                LODOP.SET_PRINT_STYLEA(0,"AlignJustify",2);
                top1+=heighty;
                top2+=heighty;
            }
        }
        else{
            if (expressName=='LB'){
                LODOP.SET_PRINT_PAGESIZE(0,width,height,"CreateCustomPage");
            }
            else{
                LODOP.SET_PRINT_PAGESIZE(0,width,height,"Sheet");
            }
            LODOP.SET_PRINT_STYLE("FontSize",12);
            LODOP.SET_PRINT_STYLE("Bold",1);
            LODOP.ADD_PRINT_HTM(0,0,"100%","100%",content);
        }

        LODOP.PREVIEW();
    }
</script>