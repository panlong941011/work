function newNavHeading(ID)
{
	info('正在弹出窗口。。。。。');
	$.get
	(
		sHomeUrl+'/system/syssolution/newnavheading?SolutionID='+ID,
		function(data)
		{
			var modal = openModal(data, '400', 110);
		}
	);
}

function sortNavHeading(ID)
{
	info('正在弹出窗口。。。。。');
	$.get
	(
		sHomeUrl+'/system/syssolution/sortnavheading?SolutionID='+ID,
		function(data)
		{
			var modal = openModal(data, '400', 430);
		}
	);
}

function sortNavTab(ID)
{
	info('正在弹出窗口。。。。。');
	$.get
	(
		sHomeUrl+'/system/syssolution/sortnavtab?HeadingID='+ID,
		function(data)
		{
			var modal = openModal(data, '400', 430);
		}
	);
}

function newNavTab(ID)
{
	info('正在弹出窗口。。。。。');
	$.get
	(
		sHomeUrl+'/system/syssolution/newnavtab?HeadingID='+ID,
		function(data)
		{
			var modal = openModal(data, 800, 450);
		}
	);
}

function editNavTab(obj)
{
	if($("input:checked[name='tabSelected[]']", obj).size() > 1) {
    	error('只能选择一个菜单');
    	return;
    } else if ($("input:checked[name='tabSelected[]']", obj).size() == 0) {
    	error('请选择一个菜单');
    	return;    
    }

	info('正在弹出窗口。。。。。');
	$.get
	(
		sHomeUrl+'/system/syssolution/editnavtab?ID='+$("input:checked[name='tabSelected[]']", obj).val(),
		function(data)
		{
			var modal = openModal(data, 800, 450);
		}
	);	
}

function delNavTab(obj)
{
    if ($("input:checked[name='tabSelected[]']", obj).size() == 0) {
    	error('至少选择一个菜单');
    	return;    
    }
    
    $(obj).closest('form').submit();    
}

function apply(ID)
{
	info('正在弹出窗口。。。。。');
	$.get
	(
		sHomeUrl+'/system/syssolution/apply?ID='+ID,
		function(data)
		{
			var modal = openModal(data, 800, 430);
		}
	);
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
    
    }
);