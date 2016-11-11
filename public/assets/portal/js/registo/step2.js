
$(function() {

    // validate signup form on keyup and submit
    $("#saveForm").validate({
        success: function(label, input) {
            input = $(input);
            var local = input.parents('.error-placer');
            if (local.length > 0) {
                var place = local.find('.place');
                input = (place.length > 0 ? place: local);
            }
            input.siblings('.success-color').remove();
            input.after('<i class="fa fa-check-circle success-color"></i>');
            input.siblings('.warning-color').remove();
            input.parent().removeClass('error');
        },
        errorPlacement: function(error, input) {
            input = $(input);
            var local = input.parents('.error-placer');
            if (local.length > 0) {
                var place = local.find('.place');
                input = (place.length > 0 ? place: local);
            }
            input.siblings('.warning-color').remove();
            input.siblings('span').remove();
            input.after('<span><font class="texto-erro">'+error.text()+'</font></span>')
            input.after('<i class="fa fa-exclamation-circle warning-color"></i>');
            input.siblings('.success-color').remove();
            input.parent().addClass('error');
        },
        customProcessStatus: function (status, response) {
            if (status === 'error' && (response.type === 'error' || response.type === 'login_error')) {
                // custom alert msg
                var modal = $('#myModal');
                modal.find('.msg').text(response.msg);
                modal.find('#close').unbind('click').click(function () {
                    modal.removeClass('in').addClass('out');
                    setTimeout(function () {
                        modal.hide().parent().hide();
                    }, 300);
                });
                modal.show().addClass('in').parent().show();
                return true;
            }
            console.log(status, response);
            return false;
        },
        showErrors: function(errorMap, errorList) {
            $("#summary").empty();
            $.each(errorMap, function(a, b){
                if ($('#' + a).length == 0)
                    $("#summary").append($('<div>').text(a + ': ' + b));
            });
            this.defaultShowErrors();
        },
        rules: {
            upload: "required"
        },
        messages: {
            upload: "Por favor, introduza um comprovativo de identidade"
        }
    });

    $("#imagem").click(function () {
        $("#upload").trigger('click');
    });
    $('#upload').change(function(){
        var fileName = $(this).val();
        $('#ficheiro').text(fileName);
    });

    $('#info-close').click(function(){

        top.location.replace("/");
    });
    $('#limpar').click(function(){
        document.location.href="/registar/step1";
    });
});