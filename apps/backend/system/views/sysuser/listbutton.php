<a href="javascript:;" class="btn green btn-sm" onclick="this.listtable.new('新建<?=$this->context->sysObject->sName?>')"> 新建 </a>
<a href="javascript:;" class="btn green btn-sm" onclick="confirmation(this, '确定要删除？', function(obj){obj.listtable.del()})"> 删除  </a>
<a href="javascript:;" class="btn green btn-sm" onclick="confirmation(this, '确定要启用？', function(obj){enable(obj.listtable)})"> 启用  </a>
<a href="javascript:;" class="btn green btn-sm" onclick="editOperator(this.listtable)"> 操作权限 </a>
<a href="javascript:;" class="btn green btn-sm" onclick="confirmation(this, '禁用之后，该人员就无法登陆系统。', function(obj){disable(obj.listtable)})"> 禁用 </a>
<a href="javascript:;" class="btn green btn-sm" onclick="confirmation(this, '密码重置为123456？', function(obj){reset(obj.listtable)})"> 重置密码  </a>
<a href="javascript:;" class="btn green btn-sm" onclick="this.listtable.export()"> 导出 </a>          
<a href="javascript:;" class="btn green btn-sm" onclick="this.listtable.refresh()"> 刷新 </a>     