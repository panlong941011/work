<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <h4 class="modal-title">商品分类</h4>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-success no-margin"> 请点击选择商品分类</div>
        </div>
        <div class="col-md-12 margin-top-10" id="treeview">
            <?=$sTreeHTML?>
        </div>
    </div>
</div>

<link href="<?=Yii::$app->homeUrl?>/js/global/plugins/jstree/dist/themes/default/style.min.css" rel="stylesheet" type="text/css" />
<script src="<?=Yii::$app->homeUrl?>/js/global/plugins/jstree/dist/jstree.min.js" type="text/javascript"></script>
<script type="text/javascript">
    $('#treeview').jstree({
        "core" : {
            "themes" : {
                "responsive": false
            }
        },
        "types" : {
            "default" : {
                "icon" : "fa fa-folder icon-state-warning icon-lg"
            },
            "file" : {
                "icon" : "fa fa-file icon-state-warning icon-lg"
            }
        },
        "plugins": ["types"]
    });

    // handle link clicks in tree nodes(support target="_blank" as well)
    $('#treeview').on('select_node.jstree', function(e,data) {
        var link = $('#' + data.selected).find('a');
        if (link.attr("href") != "#" && link.attr("href") != "javascript:;" && link.attr("href") != "") {
            if (link.attr("target") == "_blank") {
                link.attr("href").target = "_blank";
            }
            document.location.href = link.attr("href");
            return false;
        }
    });
</script>
