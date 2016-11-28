/**
 * Created by miguel on 10/02/2016.
 */
$(function () {
    function success(label, input) {
        input = $(input);
        var errorDiv = input.siblings('.error');
        input.siblings('.success-color').remove();
        input.siblings('.warning-color').remove();
        errorDiv.before('<i class="fa fa-check-circle success-color"></i>');
    }
    function error(error, input) {
        input = $(input);
        var errorDiv = input.siblings('.error');

        input.siblings('.warning-color').remove();
        input.siblings('.success-color').remove();
        errorDiv.before('<i class="fa fa-times-circle warning-color"></i>');
        errorDiv.before('<span class="warning-color">' + error.text() + '</span>');
    }

    $("#saveFormDeposits").validate({
        success: success,
        errorPlacement: error,
        rules: {
            limit_daily_deposit: {
                required: true,
                number: true,
                min: 0
            },
            limit_weekly_deposit: {
                required: true,
                number: true,
                min: 0
            },
            limit_monthly_deposit: {
                required: true,
                number: true,
                min: 0
            }
        },
        messages: {
            limit_daily_deposit: {
                required: "Introduza limite diario",
                number: "Apenas dígitos são aceites no formato x.xx",
                min: "Por favor coloque um valor maior que 0."
            },
            limit_weekly_deposit: {
                required: "Introduza limite semanal",
                number: "Apenas dígitos são aceites no formato x.xx",
                min: "Por favor coloque um valor maior que 0."
            },
            limit_monthly_deposit: {
                required: "Introduza limite mensal",
                number: "Apenas dígitos são aceites no formato x.xx",
                min: "Por favor coloque um valor maior que 0."
            }
        }
    });
    $("#saveFormBets").validate({
        success: success,
        errorPlacement: error,
        rules: {
            limit_daily_bet: {
                required: true,
                number: true,
                min: 0
            },
            limit_weekly_bet: {
                required: true,
                number: true,
                min: 0
            },
            limit_monthly_bet: {
                required: true,
                number: true,
                min: 0
            }
        },
        messages: {
            limit_daily_bet: {
                required: "Introduza limite diario",
                number: "Apenas dígitos são aceites no formato x.xx",
                min: "Por favor coloque um valor maior que 0."
            },
            limit_weekly_bet: {
                required: "Introduza limite semanal",
                number: "Apenas dígitos são aceites no formato x.xx",
                min: "Por favor coloque um valor maior que 0."
            },
            limit_monthly_bet: {
                required: "Introduza limite mensal",
                number: "Apenas dígitos são aceites no formato x.xx",
                min: "Por favor coloque um valor maior que 0."
            }
        }
    });
});