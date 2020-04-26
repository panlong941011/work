function check(ID, object)
{
    $.get('/shop/withdraw/check', {ID: ID}, function (data) {
        var modal = openModal(data, 400, 300);
    });
}

function hide_reason()
{
    $("#reason").css("display","none")
}

function show_reason()
{
    $("#reason").css("display","block")
}

function submit_button(ID)
{
    var check = $("input[name='check']:checked").val();
    var fail_reason = $("#fail_reason").val();
    $.post
    (
        '/shop/withdraw/result',
        {
            ID:ID,
            check:check,
            fail_reason:fail_reason
        },
        function(res)
        {
            var res = JSON.parse(res)
            if(res['type'] == 'success')
            {
                success(res['msg']);
                closeModal();
            }else{
                error(res['msg']);
                closeModal();
            }
        }
    )
}

function close_modal()
{
    closeModal()
}
