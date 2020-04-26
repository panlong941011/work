<?php
use myerm\backend\common\libs\SystemTime;
use myerm\backend\common\libs\LinkPager;
?>
             
<div class="row" id="quicksearchbar">
    <div class="col-md-5">
        <div class="dataTables_length" id="sample_1_length">
        	<? if ($arrConfig['bCanBat'] && !$arrConfig['bSingle']) { ?>
            <label><div class="checker"><span><input type="checkbox" class="allcheckboxes"></span></div><?=Yii::t('app', '选择所有数据')?></label>
            <? } ?>
            <? if ($arrConfig['bCanPage']) { ?>
            <label>
                <?=Yii::t('app', '显示')?>:
                <select name="lPageLimit" class="form-control input-sm input-xsmall input-inline">
                	<option value="10"<?=$arrConfig['lPageLimit'] == 10 ? "selected" : ""?>>10</option>
                    <option value="20"<?=$arrConfig['lPageLimit'] == 20 ? "selected" : ""?>>20</option>
                    <option value="50"<?=$arrConfig['lPageLimit'] == 50 ? "selected" : ""?>>50</option>
                    <option value="100"<?=$arrConfig['lPageLimit'] == 100 ? " selected" : ""?>>100</option>
                </select>
            </label>
            <? } ?>
        </div>
    </div>
    <div class="col-md-7 text-right">
        <label>
            <?=Yii::t('app', '{0}搜索', Yii::t('app', $sQuickSearchTip))?>:
            <input name="sSearchKeyWord" value="<?=$_POST['sSearchKeyWord']?>" type="search" class="form-control input-sm input-small input-inline" placeholder="<?=Yii::t('app', '请输入{0}', Yii::t('app', $sQuickSearchTip))?>" id="searchbox">
            <button id="btnSearchConfirm" type="button" class="btn red btn-sm"><?=Yii::t('app', '确定')?></button>
            <button type="button" id="btnSearchCancel" class="btn btn-outline btn-sm dark"><?=Yii::t('app', '取消')?></button>
        </label>
    </div>
</div>
<div>
    <div class="table-scrollable">
        <table class="table table-striped table-bordered table-hover table-checkable order-column dataTable no-footer table-header-fixed" id="listtable">
            <thead>
                <tr role="row">

                	<? if ($arrConfig['bCanBat']) { ?>
                    <th class="sorting_disabled">
                        <div class="checker"><span><input type="checkbox" class="pagecheckbox"></span></div> 
                    </th>
                    <? } ?>

                    <th class="sorting_disabled" style="text-align: center">序号</th>


                    <? if ($this->context->getListTableLineButton()) { ?>
                    <th class="sorting_disabled" nowrap="nowrap" style="text-align: center"> 操作 </th>
                    <? } ?>

                    <? foreach ($arrDispCol as $col) { ?>
                    <? if (stristr($col->sFieldClassType, "list")) { ?>
                    <th <? if ($arrConfig['bCanSort']) { ?><? if ($col->sDataType == 'ListTable' || $col->sDataType == 'List' || $col->sDataType == 'MultiList' || $col->sDataType == 'TextArea' || $col->sDataType == 'Virtual' || $col->sDataType == 'Bool' || $col->sDataType == 'Text') { ?>class="sorting_disabled"<? } else { ?>class="sorting"<? } ?><? } ?> sFieldAs="<?=$col->sFieldAs?>"><?=$col->sName?>&nbsp;&nbsp;&nbsp;</th>
                    <? } ?>
                    <? } ?>
                </tr>
            </thead>
            <tbody>
            <? foreach ($arrData as $i => $data) { ?>
            <tr class="gradeX <?=$i%2 ? "odd" : "even"?>" role="row">
            	
                <? if ($arrConfig['bCanBat']) { ?>
                <td>
                    <div class="checker"><span><input type="checkbox" class="checkboxes" value="<?=$data[$sysobject->sIDField]?>"></span></div>
                </td>
                <? } ?>

                <td align="center"><?=($i + 1 + ($lPage-1) * $arrConfig['lPageLimit'])?></td>


                <?=$this->context->getListTableLineButton($data, $arrConfig)?>

                <? foreach ($arrDispCol as $col) { ?>
                    <? if (stristr($col->sFieldClassType, "list")) { ?>   
                        <? if ($col->sDataType == "ListTable") { ?>
                            <? if ($data[$col->sFieldAs]) { ?>
                            <td nowrap="nowrap"><a href="javascript:;" onclick="parent.addTab($(this).text(), '<?=Yii::$app->homeUrl?>/<?=strtolower($col->sRefKey)?>/viewredirect?ID=<?=$data[$col->sFieldAs]['ID']?>&FieldID=<?=$col->ID?>')"><?=$data[$col->sFieldAs]['sName']?></a></td>
                            <? } else { ?>
                            <td>&nbsp;</td>
                            <? } ?>
                        <? } elseif ($col->sDataType == "List") { ?>
                            <? if ($data[$col->sFieldAs]) { ?>
                                <? if ($col->sEnumTable && $col->sEnumTable != "System/SysFieldEnum") { ?>                   
                                    <td nowrap="nowrap"><a href="javascript:;" onclick="parent.addTab($(this).text(), '<?=Yii::$app->homeUrl?>/<?=strtolower($col->sEnumTable)?>/viewredirect?ID=<?=$data[$col->sFieldAs]['ID']?>&FieldID=<?=$col->ID?>')"><?=$data[$col->sFieldAs]['sName']?></a></td>
                                <? } else { ?>
                                    <td nowrap="nowrap"><?=$data[$col->sFieldAs]['sName']?></td>
                                <? } ?>
                            <? } else { ?>
                                <td>&nbsp;</td>
                            <? } ?>                    
                        <? } elseif ($col->sDataType == "MultiList") { ?>
                            <? if ($data[$col->sFieldAs]) { ?>
                            <td nowrap="nowrap">
                            <?
                            $sComm = "";
                            foreach ($data[$col->sFieldAs] as $arr) { 
                            ?>
                            <?=$sComm?>
                            <? if ($col->sEnumTable != "System/SysFieldEnum") { ?>  
                                <a href="javascript:;" onclick="parent.addTab($(this).text(), '<?=Yii::$app->homeUrl?>/<?=strtolower($col->sEnumTable)?>/viewredirect?ID=<?=$arr['ID']?>&FieldID=<?=$col->ID?>')"><?=$arr['sName']?></a>
                            <? } else { ?>  
                                <?=$arr['sName']?>
                            <? } ?>
                            <? $sComm = "<br>";} ?>
                            </td>
                            <? } else { ?>
                            <td>&nbsp;</td>
                            <? } ?>                       
                        <? } elseif ($col->sDataType == "Text" && $col->sFieldAs == $sysobject->sNameField) { ?>
                            <? if ($data[$col->sFieldAs]) { ?>
                            <td nowrap="nowrap"><a href="javascript:;" onclick="parent.addTab($(this).text(), '<?=Yii::$app->homeUrl?>/<?=strtolower($col->sObjectName)?>/view?ID=<?=$data[$sysobject->sIDField]?>')"><?=$data[$col->sFieldAs]?></a></td>
                            <? } else { ?>
                            <td>&nbsp;</td>
                            <? } ?>
                        <? } elseif ($col->sDataType == "Bool") { ?>
                            <td nowrap="nowrap"><? if ($data[$col->sFieldAs]) { ?>是<? } else { ?>否<? } ?></td>
                        <? } elseif ($col->sDataType == "Float") { ?>
                            <td nowrap="nowrap"><?=$data[$col->sFieldAs] === null ? "0" : $data[$col->sFieldAs]?></td>
                        <? } elseif ($col->sDataType == "Int") { ?>
                            <td nowrap="nowrap"><?=$data[$col->sFieldAs] === null ? "0" : $data[$col->sFieldAs]?></td>
                        <? } elseif ($col->sDataType == "Date") { ?>  
                            <? if ($data[$col->sFieldAs]) { ?>
                            <td nowrap="nowrap"><?=$col->attr['dFormat'] == 'short' ? SystemTime::getShortDate($data[$col->sFieldAs], $col->attr['lTimeOffset']) : SystemTime::getLongDate($data[$col->sFieldAs], $col->attr['lTimeOffset'])?></td>
                            <? } else { ?>
                            <td>&nbsp;</td>
                            <? } ?>
                        <? } elseif ($col->sDataType == "AttachFile") { ?>       
                            <? if ($data[$col->sLinkField]) { ?>
                                <? if ($col->attr['attachType'] == 'isimg') { ?>
                                <td nowrap="nowrap"><a target="_blank" href="<?=stristr($data[$col->sLinkField], "://") ? $data[$col->sLinkField] : Yii::$app->params['sUploadUrl']."/".$data[$col->sLinkField]?>"><img <?=$col->attr['lImageWidth'] ? "width='".$col->attr['lImageWidth']."'" : "width='100'"?> <?=$col->attr['lImageHeight'] ? "height='".$col->attr['lImageHeight']."'" : "height='100'"?> src="<?=stristr($data[$col->sLinkField], "://") ? $data[$col->sLinkField] : Yii::$app->params['sUploadUrl']."/".$data[$col->sLinkField]?>" title="<?=$data[$col->sFieldAs]?>" /></a></td>
                                <? } else { ?>
                                <td nowrap="nowrap"><a target="_blank" href="<?=stristr($data[$col->sLinkField], "://") ? $data[$col->sLinkField] : Yii::$app->params['sUploadUrl']."/".$data[$col->sLinkField]?>"><?=$data[$col->sFieldAs]?></a></td>
                                <? } ?>
                            <? } else { ?>
                            <td>&nbsp;</td>
                            <? } ?>
                        <? } elseif ($col->sDataType == "TextArea") { ?>
                        	<? if (!$col->bEnableRTE) { ?>
								<td nowrap="nowrap"><?=($data[$col->sFieldAs] === null || $data[$col->sFieldAs] == "") ? "&nbsp;" : nl2br($data[$col->sFieldAs])?></td>
                        	<? } else { ?>
                            	<td><?=($data[$col->sFieldAs] === null || $data[$col->sFieldAs] == "") ? "&nbsp;" : $data[$col->sFieldAs]?></td>
                            <? } ?>
                        <? } else { ?>
                            <td nowrap="nowrap"><?=($data[$col->sFieldAs] === null || $data[$col->sFieldAs] == "") ? "&nbsp;" : $data[$col->sFieldAs]?></td>
                        <? } ?>
                    <? } ?>
                <? } ?>
            </tr>
            <? } ?>
            </tbody>
        </table>
    </div>
    <div class="row">
        <div class="col-md-5">            
			<? if ($arrConfig['bCanPage']) { ?>
            <?=Yii::t('app', '{n1}/{n2}页，共{n3}条记录', ['n1'=>$lPage, 'n2'=>$lTotalPage, 'n3'=>$lTotalCount])?>
            <? } else { ?>
            <?=Yii::t('app', '共{n1}条记录', ['n1'=>$lTotalCount])?>
            <? } ?>
            <? if ($arrConfig['bCanBat']) { ?>
            <span id="selectedstatus"></span>
            <? } ?>
        </div>

        <div class="col-md-7 text-right">


            <div class="col-md-5  text-right">
                跳转到第<input id="jumppage" value="<?=$arrConfig['lPage']?>" size="5"> 页
            </div>
            <div class="col-md-7">
			<? if ($arrConfig['bCanPage']) { ?>
            <?=LinkPager::widget(['pagination' => $pagination]) ?>
            <? } ?>
            </div>
        </div>
    </div>
</div>