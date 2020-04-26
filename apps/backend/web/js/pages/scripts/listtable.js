/**
 * 视图表格操作类
 */
function ListTable(arrConfig)
{
	if (!arrConfig) {
		arrConfig = {};
	}
	
	//每页几条
	this.lPageLimit = arrConfig.lPageLimit || 10;
	
	//排序的字段
	this.sOrderByField = arrConfig.sOrderByField || "";
	
	//排序的方向
	this.sOrderByDirection = arrConfig.sOrderByDirection || "";
	
	//总数
	this.lTotal = arrConfig.lTotal || 0;
	
	//当前的页码
	this.lPage = arrConfig.lPage || 1;
	
	//视图的ID，必填
	this.ListID = arrConfig.ListID || "";
	
	//视图的key，必填
	this.sListKey = arrConfig.sListKey || "";
	
	//选中的项
	this.arrSelected = arrConfig.arrSelected || {};
	
	//快速搜索的关键字
	this.sSearchKeyWord = arrConfig.sSearchKeyWord || "";
	
	//是否单选
	this.bSingle = arrConfig.bSingle || 0;
	
	//对象的url
	this.sUrl = arrConfig.sUrl || "";
	
	this.sDataRegion = arrConfig.sDataRegion || "";	
	
	//
	this.sExtra = arrConfig.sExtra || "";
	
	//视图引用的对象，必填
	this.container = arrConfig.container;

	//视图数据的来源，必填
	this.sDataUrl = arrConfig.sDataUrl;
	
	var ListTable = this;
	
	//容器对视图对象的引用
	this.container.ListTable = this;
	
	//高级搜索框是否已经加载完毕
	this.advancedSearchLoaded = false;

	this.onLoad = arrConfig.onLoad ? $.proxy(arrConfig.onLoad, this) : function(){};

	$(".search-toolbar form", this.container).prop("listtable", this);
	$(".search-toolbar .multiselect", this.container).each
	(
		function () {
			$(this).multiselect({
				maxHeight : 200,
				nSelectedText : '',
				nonSelectedText : '请选择。。。',
				selectAllText : '全选',
				enableFiltering : true,
				includeSelectAllOption: true,
			});
		}
	);


	//初始化
	this.init = function()
	{
		//切换每页记录数
		$("select[name='lPageLimit']", this.container).unbind().change
		(
			function ()
			{
				ListTable.emptySelected();
				ListTable.lPageLimit = $(this).val();
				ListTable.lPage = 1;
				ListTable.loadData();
			}
		);
	
		$("#btnSearchCancel", this.container).unbind().click
		(
			function ()
			{
				ListTable.emptySelected();
				ListTable.lPage = 1;
				$("input[name='sSearchKeyWord']", ListTable.container).val('');
				ListTable.sSearchKeyWord = "";
				ListTable.loadData();
			}
		);	
		
		$("#advancedBtn", this.container).unbind().click
		(
			function ()
			{
				if ($(".search-toolbar", ListTable.container).is(":hidden")) {				
					$("i", this).removeClass('fa-angle-down').addClass('fa-angle-up');
					$(".search-toolbar", ListTable.container).show();
				} else {
					$(".search-toolbar", ListTable.container).hide();
					$("i", this).removeClass('fa-angle-up').addClass('fa-angle-down');
				}
			}
		);		
		
		$("#btngroup", this.container).children().each
		(
			function () {
				$(this).prop("listtable", ListTable);
			}
		);
		
		$("#searchbox", this.container).unbind().keypress
		(
			function()
			{
				if (event.which == 13) {
					$("#btnSearchConfirm", ListTable.container).click();
				}
			}
		);

        $("#jumppage", this.container).unbind().keypress
        (
            function()
            {
                if (event.which == 13) {
                	if ($(this).val() == 0) {
                        $(this).val(1);
					}

                    ListTable.lPage = $(this).val();
                    ListTable.loadData();
                }
            }
        );
		
		
		$('.datepicker', this.container).each
		(
			function()
			{
				if ($(this).attr('colindex') % 3 == 0) {
					var opens = "right";
				} else if ($(this).attr('colindex') % 3 == 2) {
					var opens = "left";
				} else {
					var opens = "right";
				}
				
				moment.locale('zh-cn');
				
				var datePicker = this;
				
				$(this).daterangepicker({
						opens: opens,
						format: 'YYYY-MM-DD',
						separator: ' to ',
						startDate: moment().subtract(30, 'days'),
						endDate: moment(),
						locale:{customRangeLabel:'自定义', applyLabel:'确定', cancelLabel:'取消'},
						ranges: {
							'今天': [moment(), moment()],
							'昨天': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
							'过去7天': [moment().subtract(6, 'days'), moment()],
							'过去30天': [moment().subtract(29, 'days'), moment()],
							'今年': [moment().startOf('year'), moment().endOf('year')],
							'本月': [moment().startOf('month'), moment().endOf('month')],
							'上个月': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
						},
					},
					function (start, end) {
						$('input', datePicker).val(start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD'));
					}
				); 						
			}
		
		);
	
		this.search = function()
		{
			this.emptySelected();
			this.lPage = 1;
			$("input[name='sSearchKeyWord']", this.container).val('');
			this.sSearchKeyWord = "";
			this.loadData();
		}
		
		this.closeSearch = function()
		{
			$(".search-toolbar", this.container).hide();
			$("#advancedBtn i", this.container).removeClass('fa-angle-up').addClass('fa-angle-down');
		}
	
		this.emptySelected = function()
		{
			ListTable.arrSelected = {};
			$(".allcheckboxes", this.container).parent().removeClass("checked");
		}
		
		this.getSelectedLength = function()
		{
			if (this.arrSelected == "all") {
				var lLength = -1;
			} else {			
				var lLength = 0;
				for (i in this.arrSelected) {
					lLength++;
				}
			}		
			
			return lLength;
		}
		
		this.emptySearchValue = function()
		{			
			$(".search-field", this.container).each
			(
				function ()
				{
					if ($(this).attr('sDataType') == 'List' || $(this).attr('sDataType') == 'MultiList') {

						
						$('option', this).each(function(element) {
							  $(this).parent().multiselect('deselect', $(this).val());
						});
						


					} else {
						$(this).val('');	
					}			
				}
			);			
			
		}

		this.getFirstSelected = function()
		{
			for (i in this.arrSelected) {
				return i;
			}
			
			return "";
		}
		
		this.updateSelectedStatus = function()
		{
			if (this.arrSelected == "all") {
				var text = "，已选中所有数据";
			} else {			
				var lLength = 0;
				for (i in this.arrSelected) {
					lLength++;
				}
				
				var text = "，已选中<span class=\"font-red\">"+lLength+"</span>条记录";
			}		
			
			$("#selectedstatus", this.container).html(text);
		}
		
		//确定复选框的状态
		this.checkSelectAll = function()
		{
			if (this.arrSelected == "all") {
				$(".pagecheckbox", this.container).parent().addClass("checked");
				$(".checkboxes", this.container).parent().addClass("checked").closest("tr").addClass("active");;
			} else {			
				if ($("#listtable tbody tr", this.container).size() > 0) {
					$(".pagecheckbox", this.container).parent().addClass("checked");
					$(".checkboxes", this.container).each
					(
						function () 
						{
							var ID = $(this).val();
							
							if (!$(this).parent().hasClass("checked")) {
								$(".pagecheckbox", ListTable.container).parent().removeClass("checked");
								$(this).parent().closest("tr").removeClass("active");;
							} else {
								$(this).parent().closest("tr").addClass("active");;
							}						
						}
					);
				} else {
					$(".pagecheckbox", this.container).parent().removeClass("checked");
					$(".allcheckboxes", this.container).parent().removeClass("checked");
				}
			}
			
			this.updateSelectedStatus();
		}


		//点击了搜索确定按钮
		$("#btnSearchConfirm", this.container).unbind().click
		(
			function ()
			{
				ListTable.emptySearchValue();
				ListTable.emptySelected();
				ListTable.lPage = 1;
				ListTable.sSearchKeyWord = $("input[name='sSearchKeyWord']", ListTable.container).val();
				ListTable.loadData();
			}
		);		

		//计算是否有高级搜索的项目，有则显示高级搜索的按钮
		if ($(".search-field", this.container).size() > 0) {
			$("#advancedBtn", this.container).removeClass("hide");
		}

		$(".checkboxes", this.container).unbind().click
		(
			function () {
				
				if (ListTable.bSingle) {
					
					ListTable.arrSelected = {};
					
					if ($(this).parent().hasClass("checked")) {
						$(this).parent().removeClass("checked");
					} else {
						$(".checkboxes", ListTable.container).parent().removeClass("checked");
						$(this).parent().addClass("checked");
						ListTable.arrSelected[$(this).val()] = $(this).val();
					}
					
				} else {
					delete ListTable.arrSelected[$(this).val()];
					
					if ($(this).parent().hasClass("checked")) {
						$(".allcheckboxes", ListTable.container).parent().removeClass("checked");
						$(this).parent().removeClass("checked");
					} else {
						$(this).parent().addClass("checked");
						ListTable.arrSelected[$(this).val()] = $(this).val();
					}
				}
				
				ListTable.checkSelectAll();
			}
		);
		
		$(".pagecheckbox", this.container).unbind().click
		(
			function () 
			{
				if ($(this).parent().hasClass("checked")) {
					ListTable.arrSelected = {};
					$(".allcheckboxes", ListTable.container).parent().removeClass("checked");
					$(this).parent().removeClass("checked");
					$(".checkboxes", ListTable.container).parent().removeClass("checked").closest("tr").removeClass("active");
					
					$(".checkboxes", ListTable.container).each
					(
						function () 
						{
							delete ListTable.arrSelected[$(this).val()];
						}						
					);
				} else {					
					$(this).parent().addClass("checked");
					$(".checkboxes", ListTable.container).parent().addClass("checked").closest("tr").removeClass("active");										
					$(".checkboxes", ListTable.container).each
					(
						function () 
						{
							ListTable.arrSelected[$(this).val()] = $(this).val();
						}						
					);
				}
				
				ListTable.checkSelectAll();
			}
		);
		
		$(".allcheckboxes", this.container).unbind().click
		(
			function () {
				if ($(this).parent().hasClass("checked")) {
					ListTable.arrSelected = {};
					$(this).parent().removeClass("checked");
					$(".checkboxes", ListTable.container).parent().removeClass("checked").closest("tr").removeClass("active");
					$(".pagecheckbox", ListTable.container).parent().removeClass("checked");
				} else {
					ListTable.arrSelected = "all";
					$(this).parent().addClass("checked");
					$(".checkboxes", ListTable.container).parent().addClass("checked").closest("tr").addClass("active");
					$(".pagecheckbox", ListTable.container).parent().addClass("checked");
				}
				
				ListTable.checkSelectAll();
			}
		);
		
		$(".checkboxes", this.container).each
		(
			function () 
			{
				var ID = $(this).val();

				if (typeof ListTable.arrSelected[ID] != "undefined") {
					$(this).parent().addClass("checked").closest("tr").removeClass("active");
				}									
			}
		);			
		
		$("#listtable th.sorting_asc, #listtable th.sorting_desc, #listtable th.sorting").unbind().click
		(
			function ()
			{
				ListTable.emptySelected();
				ListTable.lPage = 1;				
				ListTable.sOrderByField = $(this).attr('sFieldAs');
				
				if ($(this).hasClass('sorting_asc') || $(this).hasClass('sorting')) {
					$sClass = "sorting_desc";
					ListTable.sOrderByDirection = "DESC";
				} else {
					$sClass = "sorting_asc";
					ListTable.sOrderByDirection = "ASC";
				}
				
				$("#listtable th[class!='sorting_disabled']").removeClass().addClass('sorting');
				$(this).removeClass().addClass($sClass);
				
				ListTable.loadData();
			}
		);
		
		if (ListTable.sOrderByDirection == "ASC") {
			$("#listtable th[sFieldAs='"+ListTable.sOrderByField+"']").removeClass().addClass("sorting_asc");
		} else {
			$("#listtable th[sFieldAs='"+ListTable.sOrderByField+"']").removeClass().addClass("sorting_desc");
		}
		
		$(".pagination a", ListTable.container).unbind().click
		(
			function ()
			{
				ListTable.lPage = $(this).data('page');
				ListTable.loadData();
				$.scrollTo(ListTable.container, 300);
				
				return false;
			}
		);

		this.checkSelectAll();
		this.closeSearch();
	}
	
	this.getPostData = function()
	{
		var sSelectedID = "";
		if (this.arrSelected == "all") {
			sSelectedID = "all";	
		} else {
			var sSelectedID = "";
			var sComm = "";
			for (i in this.arrSelected) {
				sSelectedID += sComm + i;
				sComm = ";";
			}		
		}		

		var postData = {
			lPageLimit : this.lPageLimit,
			sOrderBy : this.sOrderByField ? this.sOrderByField+" "+this.sOrderByDirection : "",
			lPage : this.lPage,
			ListID : this.ListID,
			sListKey : this.sListKey,
			sSearchKeyWord : this.sSearchKeyWord,
			sDataRegion : this.sDataRegion,
			bSingle : this.bSingle,
			sSelectedID : sSelectedID,
			sExtra : this.sExtra,
		};

		$(".search-field", this.container).each
		(
			function ()
			{
				if ($(this).val() != "" && $(this).val() != null) {
					
					if ($(this).attr('sDataType') == 'Date') {
						var arrDatePart = $(this).val().split(" - ");
						postData['arrSearchField['+$(this).attr('name')+'][sValue]'] = "Oper=between/Par1=SomeDate_"+arrDatePart[0]+"/Par2=SomeDate_"+arrDatePart[1];
					} else {
						postData['arrSearchField['+$(this).attr('name')+'][sValue]'] = $(this).val();
					}
					
					postData['arrSearchField['+$(this).attr('name')+'][sOper]'] = $(this).attr('soper');
				} else if ($(this).attr('soper') == 'benull') {
					postData['arrSearchField['+$(this).attr('name')+'][sOper]'] = $(this).attr('soper');
				} else if ($(this).attr('soper') == 'notnull') {
					postData['arrSearchField['+$(this).attr('name')+'][sOper]'] = $(this).attr('soper');
				}
			}
		);

		return postData;
	}

	//加载数据
	this.loadData = function ()
	{
		$(this.container).mask('');
		
		var postData = this.getPostData();

		$.post
		(
			this.sDataUrl,
			postData,
			function(data)
			{
				$("#listtable-container", ListTable.container).html(data);
				$(ListTable.container).unmask('');
				ListTable.init();

				if (ListTable.lPage != 1) {
					if ($("#listtable tbody tr", ListTable.container).size() == 0) {
						ListTable.lPage = 1;
						ListTable.loadData();
					}
				}
				
				ListTable.onLoad();
			}
		)
	}
	
	this.refresh = function ()
	{
		this.emptySelected();
		this.loadData();
	}
	
	this.new = function (sTabName)
	{
		parent.addTab(sTabName, this.sUrl+'/new');
	}
	
	this.edit = function (sTabName)
	{
		var lLength = this.getSelectedLength();
		if (lLength == 0) {
			error("必须选择一条记录。");
			return;
		} else if (lLength != 1) {
			error("只能选择一条记录。");
			return;			
		}

		parent.addTab(sTabName, this.sUrl+'/edit?ID='+this.getFirstSelected());
	}
	
	this.clone = function (sTabName)
	{
		var lLength = this.getSelectedLength();
		if (lLength == 0) {
			error("必须选择一条记录。");
			return;
		} else if (lLength != 1) {
			error("只能选择一条记录。");
			return;			
		}

		parent.addTab(sTabName, this.sUrl+'/clone?ID='+this.getFirstSelected());
	}	
	
	this.changeOwner = function()
	{		
		var lLength = this.getSelectedLength();
		if (lLength == 0) {
			error("至少选择一条记录。");
			return;
		}

		$.get
		(
			this.sUrl+'/changeowner',
			function(data)
			{
				var modal = openModal(data, 600, 250);
				$(modal).prop('listtable', ListTable);
			}
		);
	}
	
	this.export = function(OwnerID)
	{		
		var lLength = this.getSelectedLength();
		if (lLength == 0) {
			error("至少选择一条记录。");
			return;
		}

		var sSelectedID = "";
		if (this.arrSelected == "all") {
			sSelectedID = "all";	
		} else {
			var sSelectedID = "";
			var sComm = "";
			for (i in this.arrSelected) {
				sSelectedID += sComm + i;
				sComm = ";";
			}		
		}

		$(document.formdata).empty();
		$(document.formdata).attr('action', this.sUrl+'/export');
		$(document.formdata).append("<input type='hidden' name='lPageLimit' value='"+this.lPageLimit+"' />");
		$(document.formdata).append("<input type='hidden' name='sOrderBy' value='"+(this.sOrderByField ? this.sOrderByField+" "+this.sOrderByDirection : "")+"' />");
		$(document.formdata).append("<input type='hidden' name='lPage' value='"+this.lPage+"' />");
		$(document.formdata).append("<input type='hidden' name='ListID' value='"+this.ListID+"' />");
		$(document.formdata).append("<input type='hidden' name='sListKey' value='"+this.sListKey+"' />");
		$(document.formdata).append("<input type='hidden' name='sSearchKeyWord' value='"+this.sSearchKeyWord+"' />");
		$(document.formdata).append("<input type='hidden' name='sSelectedID' value='"+sSelectedID+"' />");
		$(document.formdata).append("<input type='hidden' name='sExtra' value='"+this.sExtra+"' />");
				
		$(".search-field", this.container).each
		(
			function ()
			{
				if ($(this).hasClass('multiselect') && $(this).val() == null) {
					return;
				}				
				
				if ($(this).val() != "") {
                    if ($(this).attr('sDataType') == 'Date') {
                        var arrDatePart = $(this).val().split(" - ");
                        $(document.formdata).append("<input type='hidden' name='arrSearchField["+$(this).attr('name')+"][sValue]' value='Oper=between/Par1=SomeDate_"+arrDatePart[0]+"/Par2=SomeDate_"+arrDatePart[1]+"' />");
                        $(document.formdata).append("<input type='hidden' name='arrSearchField["+$(this).attr('name')+"][sOper]' value='between' />");
                    } else {
                        if (typeof $(this).val() == 'object') {
                        	for (k in $(this).val()) {
                                $(document.formdata).append("<input type='hidden' name='arrSearchField["+$(this).attr('name')+"][sValue]["+k+"]' value='"+$(this).val()[k]+"' />");
							}
						} else {
                            $(document.formdata).append("<input type='hidden' name='arrSearchField["+$(this).attr('name')+"][sValue]' value='"+$(this).val()+"' />");
						}
                        $(document.formdata).append("<input type='hidden' name='arrSearchField["+$(this).attr('name')+"][sOper]' value='"+$(this).attr('soper')+"' />");
                    }
				}				
			}
		);		

		$("body").append("<iframe src='"+sHomeUrl+'/loadwin.html'+"' style='display:none'></iframe>");	
		
		this.emptySelected();
		this.loadData();
	}
	
	
	this.changeOwnerSave = function(OwnerID)
	{	
		var lLength = this.getSelectedLength();
		if (lLength == 0) {
			error("至少选择一条记录。");
			return;
		}
		
		var postData = this.getPostData();
		postData.OwnerID = OwnerID;
		
		info("正在提交更改拥有者。。。");
		
		$.post
		(
			this.sUrl+'/changeownersave',
			postData,
			function(data)
			{
				clearToastr();
				
				eval("var ret = "+data);
				if (ret.bSuccess) {
					closeModal();
					success(ret.sMsg);
					ListTable.emptySelected();			
					ListTable.loadData();
				} else {
					error(ret.sMsg);
				}
			}
		)	
	}
	
	this.manualshare = function ()
	{
		var lLength = this.getSelectedLength();
		if (lLength == 0) {
			error("至少选择一条记录。");
			return;
		}
		
		$.get
		(
			this.sUrl+'/manualshare',
			function(data)
			{
				var modal = openModal(data, 800, 430);
				$(modal).prop('listtable', ListTable);
			}
		);		
	}
	
	this.manualShareSave = function(arrShareData)
	{	
		var lLength = this.getSelectedLength();
		if (lLength == 0) {
			error("至少选择一条记录。");
			return;
		}
		
		var postData = this.getPostData();
		postData.arrShareData = arrShareData;
		
		info("正在提交手动共享。。。");
		
		$.post
		(
			this.sUrl+'/manualsharesave',
			postData,
			function(data)
			{
				clearToastr();
				
				eval("var ret = "+data);
				if (ret.bSuccess) {
					closeModal();
					success(ret.sMsg);
					ListTable.emptySelected();			
					ListTable.loadData();
				} else {
					error(ret.sMsg);
				}
			}
		)	
	}	
	
	
	
	this.del = function ()
	{
		var lLength = this.getSelectedLength();
		if (lLength == 0) {
			error("至少选择一条记录。");
			return;
		}
		
		var postData = this.getPostData();
		
		$(this.container).mask('');
		
		$.post
		(
			this.sUrl+'/del',
			postData,
			function(data)
			{
				eval(data);
				if (ret.bSuccess) {
					success(ret.sMsg);
					ListTable.emptySelected();
					ListTable.loadData();
				} else {
					error(ret.sMsg);
					$(ListTable.container).unmask('');
				}
			}
		)
	}
}

