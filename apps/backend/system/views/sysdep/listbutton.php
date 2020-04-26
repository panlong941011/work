<a href="javascript:;" class="btn green btn-sm" onclick="this.listtable.new('新建<?=$this->context->sysObject->sName?>')"> 新建 </a>
<a href="javascript:;" class="btn green btn-sm" onclick="viewOrgChart()"> 层次 </a>
<a href="javascript:;" class="btn green btn-sm" onclick="editOperator(this.listtable)"> 操作权限 </a>
<a href="javascript:;" class="btn green btn-sm" onclick="confirmation(this, '确定要删除？', function(obj){obj.listtable.del()})"> 删除 </a> 
<a href="javascript:;" class="btn green btn-sm" onclick="this.listtable.refresh()"> 刷新 </a>     