/**
 * Created by miguel on 22/02/2016.
 */

(function(){
    var max = parseFloat($('#available').val());

    $("#saveForm").validate({
        success: function (label, input) {
            input = $(input);
            var errorDiv = input.siblings('.error');
            input.siblings('.success-color').remove();
            input.siblings('.warning-color').remove();
            errorDiv.before('<i class="fa fa-check-circle success-color"></i>');
        },
        errorPlacement: function (error, input) {
            input = $(input);
            var errorDiv = input.siblings('.error');

            input.siblings('.warning-color').remove();
            input.siblings('.success-color').remove();
            errorDiv.before('<i class="fa fa-times-circle warning-color"></i>');
            errorDiv.before('<span class="warning-color">' + error.text() + '</span>');
        },
        rules: {
            withdrawal_value: {
                required: true,
                digits: true,
                max: max,
                min: 1
            },
            password: {
                required: true,
            },
        },
        messages: {
            withdrawal_value: {
                required: "Preencha o valor a levantar",
                digits: "Apenas digitos são aceites",
                max: "Não possuí saldo suficiente para o levantamento pedido",
                min: "Preencha um valor superior a zero",
            },
            password: {
                required: 'Preencha a sua password'
            },
        }
    });

})();