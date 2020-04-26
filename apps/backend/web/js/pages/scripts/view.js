function changeOwner(ID)
{
	$.get
	(
		sHomeUrl+'/'+sObjectName+'/changeowner?ID='+ID,
		function(data)
		{
			var modal = openModal(data, 600, 250);
		}
	);
}

function changeOwnerSave(ID, NewOwnerID)
{
	$.post
	(
		sHomeUrl+'/'+sObjectName+'/changeownersave?ID='+ID,
		{OwnerID:NewOwnerID},
		function(data)
		{
			eval("var ret = "+data);
			if (ret.bSuccess) {
				closeModal();
				success(ret.sMsg);
			} else {
				error(ret.sMsg);
				$('body').unmask('');
			}
		}
	)			
}
