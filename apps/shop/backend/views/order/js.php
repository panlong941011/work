function ship(ID, object)
{
    var listtable = $(object).closest(".tab-pane").find("#btngroup a").prop("listtable");
    $('#myermmodal').prop('listtable', listtable);
    $.get('/shop/order/alertship', {ID: ID}, function (data) {
        if (data.bSuccess) {
        openModal(data.data, 800, 400)
        } else {
        error(data.sMsg);
        }

    });
}

function modifyShip(ID, object)
{
    var listtable = $(object).closest(".tab-pane").find("#btngroup a").prop("listtable");
    $('#myermmodal').prop('listtable', listtable);
    $.get('/shop/order/alertmodifyship', {ID: ID}, function (data) {
        if (data.bSuccess) {
            openModal(data.data, 800, 500)
        } else {
            error(data.sMsg);
        }
    });
}


