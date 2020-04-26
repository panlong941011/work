<link href="/js/global/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css" rel="stylesheet" type="text/css"/>
<link href="/js/global/plugins/typeahead/typeahead.css" rel="stylesheet" type="text/css"/>
<script src="/js/global/plugins/bootstrap-tagsinput/bootstrap-tagsinput.min.js" type="text/javascript"></script>
<script src="/js/global/plugins/typeahead/handlebars.min.js" type="text/javascript"></script>
<script src="/js/global/plugins/typeahead/typeahead.bundle.min.js" type="text/javascript"></script>


<input type="text" value="" id="object_tagsinput" class="form-control"
       name="arrObjectData[<?= $field->sObjectName ?>][<?= $field->sFieldAs ?>][]"><br>
<?
$arrID = [];
foreach ($data as $d) {
    $arrID[$d['ID']] = 1;
}
?>
<select class="form-control" sFieldAs="<?= $field->sFieldAs ?>">
    <? foreach ($field->options as $option) { ?>
        <? if (!$arrID[$option['ID']]) { ?>
            <option value="<?= $option['ID'] ?>"><?= $option['sName'] ?></option>
        <? } ?>
    <? } ?>
</select>
<a href="javascript:;" class="btn red" id="object_tagsinput_add">添加标签</a>
<script>
    jQuery(document).ready(function () {
        var elt = $('#object_tagsinput');

        elt.tagsinput({
            itemValue: 'value',
            itemText: 'text',
        });

        <?
        $arrID = [];
        foreach ($data as $d) {
            ?>
        elt.tagsinput('add', {
            "value": "<?=$d['ID']?>",
            "text": "<?=$d['sName']?>",
        });
        <?
        }
        ?>

    });

    $("#object_tagsinput_add").click
    (
        function () {
            $('#object_tagsinput').tagsinput('add', {
                "value": $("select[sFieldAs='<?=$field->sFieldAs?>']").val(),
                "text": $("select[sFieldAs='<?=$field->sFieldAs?>'] option:selected").text(),
            });

            $("select[sFieldAs='<?=$field->sFieldAs?>'] option:selected").remove();
        }
    )

    $('#object_tagsinput').on('beforeItemRemove', function (event) {
        var tag = event.item;
        // Do some processing here

        $("select[sFieldAs='<?=$field->sFieldAs?>']").append("<option value='" + tag.value + "'>" + tag.text + "</option>");

    });
</script>