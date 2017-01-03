/**
 * Created by miguel on 22/02/2016.
 */

(function(){
    var max = parseFloat($('#available').val());

    $("#saveForm").validate({
        success: function (label, ori) {
            ori = $(ori);
            input = $(ori);
            var local = input.parents('.error-placer');
            if (local.length > 0) {
                var place = local.find('.place');
                input = (place.length > 0 ? place: local);
            }
            ori.siblings('.success-color').remove();
            ori.after('<i class="fa fa-check-circle success-color"></i>');
            ori.siblings('.warning-color').remove();
            input.parent().removeClass('error');
        },
        errorPlacement: function (error, ori) {
            ori = $(ori);
            input = $(ori);
            var local = input.parents('.error-placer');
            if (local.length > 0) {
                var place = local.find('.place');
                input = (place.length > 0 ? place: local);
            }
            ori.siblings('.warning-color').remove();
            input.siblings('span').remove();
            input.after('<span><font class="texto-erro">'+error.text()+'</font></span>')
            ori.after('<i class="fa fa-exclamation-circle warning-color"></i>');
            ori.siblings('.success-color').remove();
            input.parent().addClass('error');
        },
        rules: {
            withdrawal_value: {
                required: true,
                digits: true,
                max: max,
                min: 10
            }
        },
        messages: {
            withdrawal_value: {
                required: "Preencha o valor a levantar",
                digits: "Apenas digitos são aceites",
                max: "Não possuí saldo suficiente para o levantamento pedido",
                min: "Preencha um valor superior a 10.",
            }
        }
    });

})();