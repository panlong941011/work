<div class="row">
<?php if (isset($productID) && !empty($productID)):?>
    <?php if ($type=="view"):?>
        <?php foreach ($data as $k=>$v):?>
            <div>
                <div class="col-md-4">
                    <label><?=$k?>:</label>
                    <input type="text" class="form-control" sdatatype="Text" readonly="readonly" placeholder="" value="<?=$v?>" name="arrObjectData[Shop/Product][ParameterArray][<?=$k?>]">
                </div>
            </div>
        <?endforeach;?>
    <?php else:?>
        <?php foreach ($data as $k=>$v):?>
            <div>
                <div class="col-md-4">
                    <label><?=$k?>:</label>
                    <input type="text" class="form-control" sdatatype="Text" placeholder="" value="<?=$v?>" name="arrObjectData[Shop/Product][ParameterArray][<?=$k?>]">
                </div>
            </div>
        <?endforeach;?>
    <?php endif;?>

<?php else:?>
    <?php foreach ($data as $k=>$v):?>
        <div>
            <div class="col-md-4">
                <label><?=$v?>:</label>
                <input type="text" class="form-control" sdatatype="Text" placeholder="" value="" name="arrObjectData[Shop/Product][ParameterArray][<?=$v?>]">
            </div>
        </div>
    <?endforeach;?>
<?php endif;?>
</div>

