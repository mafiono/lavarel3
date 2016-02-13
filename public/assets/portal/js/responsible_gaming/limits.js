/**
 * Created by miguel on 10/02/2016.
 */
$(function () {

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
            limit_daily: {
                required: true,
                number: true
            },
            limit_weekly: {
                required: true,
                number: true
            },
            limit_monthly: {
                required: true,
                number: true
            },
        },
        messages: {
            limit_daily: {
                required: "Introduza limite diario",
                number: "Apenas dígitos são aceites no formato x.xx",
            },
            limit_weekly: {
                required: "Introduza limite semanal",
                number: "Apenas dígitos são aceites no formato x.xx",
            },
            limit_monthly: {
                required: "Introduza limite mensal",
                number: "Apenas dígitos são aceites no formato x.xx",
            }
        }
    });

});