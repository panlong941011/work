function editOperator(listTable)
{
	var lLength = listTable.getSelectedLength();
	if (lLength == 0) {
		error("必须选择一条记录。");
		return;
	} else if (lLength != 1) {
		error("只能选择一条记录。");
		return;			
	}

	var sObjectName = listTable.getFirstSelected();
	parent.addTab('编辑操作权限', sHomeUrl+'/system/sysobject/editoperator?sObjectName='+sObjectName);
}

function autoShare(listTable)
{
	var lLength = listTable.getSelectedLength();
	if (lLength == 0) {
		error("必须选择一条记录。");
		return;
	} else if (lLength != 1) {
		error("只能选择一条记录。");
		return;			
	}

	var sObjectName = listTable.getFirstSelected();
	parent.addTab('查看自动共享', sHomeUrl+'/system/sysobject/autoshare?sObjectName='+sObjectName)   
}

$(".checker span").click
(
	function ()
    {
    	if($(this).find("input").attr('checked')) { 
        	$(this).removeClass("checked");
            $(this).find("input").attr('checked', false);
        } else {
        	$(this).addClass("checked");
            $(this).find("input").attr('checked', true);
        }
        
        isRowAllChecked($(this).closest('tr'));
    }
);

$(".checkrowall").click
(
	function ()
    {
    	if($(this).attr('checked')) {
        	$(this).parent().removeClass("checked");
            $(this).attr('checked', false);
            
    		$(this).closest('tr').find('.checkbox').attr('checked', false);
    		$(this).closest('tr').find('.checkbox').parent().removeClass("checked");
    	} else {
        	$(this).parent().addClass("checked");
            $(this).attr('checked', true); 
            
    		$(this).closest('tr').find('.checkbox').attr('checked', true);
    		$(this).closest('tr').find('.checkbox').parent().addClass("checked");               	
    	}

        return false;
    }
);

function checkTableAll(obj)
{
	$(".checker span", obj).find("input").attr('checked', true);
	$(".checker span", obj).addClass("checked");
}

function uncheckTableAll(obj)
{
	$(".checker span", obj).find("input").attr('checked', false);
	$(".checker span", obj).removeClass("checked");
}

function isRowAllChecked(row)
{
	$(".checkrowall", row).attr('checked', true);
	$(".checkrowall", row).parent().addClass("checked");
	
	$("input.checkbox", row).each
	(
		function()
		{
			if (!$(this).attr('checked')) {
            	$(".checkrowall", row).attr('checked', false);
            	$(".checkrowall", row).parent().removeClass("checked");				
			}
		}
	);
}

function checkAll()
{
	$(".checker span").find("input").attr('checked', true);
	$(".checker span").addClass("checked");
}

function uncheckAll()
{
	$(".checker span").find("input").attr('checked', false);
	$(".checker span").removeClass("checked");
}

function expandAll()
{
	$("#operatable.portlet-body").show();
	$(".tools a").removeClass('expand').addClass('collapse');
}

function collapseAll()
{
	$("#operatable.portlet-body").hide();
	$(".tools a").removeClass('collapse').addClass('expand');
}

function showRef(sObjectName, sRefObjectName, sFieldAs)
{
	info('正在弹出窗口。。。。。');
	$.post
	(
		sHomeUrl+'/'+sRefObjectName.toLowerCase()+'/showref?sFieldAs='+sFieldAs+'&sObjectName='+encodeURI(sObjectName),
		{ID:$("input[name='arrObjectData["+sObjectName+"]["+sFieldAs+"]']").val(), sName:$("input[name='arrObjectData["+sObjectName+"]["+sFieldAs+"Name]']").val()},
		function(data)
		{
			if (sRefObjectName == 'System/SysUser') {
				var modal = openModal(data, 600, 250);
			} else {
				var modal = openModal(data, '960', $(window).height() * 0.8);
			}
		}
	);
}

function refSave(sObjectName, sFieldAs, data)
{  
    $("input[name='"+sFieldAs+"']").val(data.ID);
    $("input[name='"+sFieldAs+"name']").val(data.sName);
}

function objectSubmit()
{
	clearToastr();
    
	var source = $("select[name='source']").val();
	if ($("input[name='source"+source+"id']").val() == "") {
    	error("请选择共享源。");
        return;
    }

	var target = $("select[name='target']").val();
	if ($("input[name='target"+target+"id']").val() == "") {
    	error("请选择共享目的。");
        return;
    }
    
	document.objectform.submit();
}

function changeSource(obj, source)
{
	$("div[sObjectName='dep'], div[sObjectName='role'], div[sObjectName='team']", obj).hide();
    $("div[sObjectName='"+source+"'], #sourceinclude", obj).show();    
    
    if (source == 'team') {
    	$("#sourceinclude").hide();
    }
}

function changeTarget(obj, target)
{
	$("div[sObjectName='dep'], div[sObjectName='role'], div[sObjectName='team']", obj).hide();
    $("div[sObjectName='"+target+"'], #targetinclude", obj).show(); 
    
    if (target == 'team') {
    	$("#targetinclude").hide();
    }       
}

