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

	var ID = listTable.getFirstSelected();
	parent.addTab('编辑团队操作权限', sHomeUrl+'/system/systeam/editoperator?ID='+ID);
}

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