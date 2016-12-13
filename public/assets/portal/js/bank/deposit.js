/**
 * Created by miguel on 22/02/2016.
 */

(function(){

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
                min: "O valor minimo é 10€.",
            }
        }
    });
    var dpArea = $('#deposit_area');
    var tbArea = $('#deposit_tb');

    var total = $('#total');
    var field = $('#deposit_value').on('change keyup blur', function () {
        var val = field.val();
        if (field.valid()) {
            var value = parseFloat(val);
            total.val(value.toFixed(2));
        } else {
            total.val('0.00');
        }
    });
    $('#saveForm input[name=payment_method]').on('change', function () {
        var checked = $('#method_tb').is(':checked');
        dpArea.toggle(!checked);
        tbArea.toggle(checked);
    });
})();