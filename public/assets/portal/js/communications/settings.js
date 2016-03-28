    
function onSettingChange()
{
    var checked = $(this).prop('checked') ? 1 : 0;

    var type = $(this).prop('id');

    $('.alert.alert-danger').hide();   
    $('.alert.alert-success').hide();

    $.ajax({
        type: "POST",
        url: '/comunicacao/definicoes',
        data: {
            'type': type,
            'value': checked
        },
        success:function(response){         
            if (response.status == 'success'){
                $('.alert.alert-success').find('.msg').text(response.msg);
                $('.alert.alert-success').show();
            }else{
                $('.alert.alert-danger').find('.msg').text(response.msg);
                $('.alert.alert-danger').show();                
            }
        }
    });
    
}

$(function() {

    $('.cmn-toggle').on('click', onSettingChange);

});