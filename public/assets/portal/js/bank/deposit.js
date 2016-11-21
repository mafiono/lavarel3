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
            deposit_value: {
                required: true,
                digits: true,
                min: 10
            }
        },
        messages: {
            deposit_value: {
                required: "Preencha o valor a depositar",
                digits: "Apenas digitos são aceites",
                min: "O valor minimo de deposito é 10€.",
            }
        }
    });

})();