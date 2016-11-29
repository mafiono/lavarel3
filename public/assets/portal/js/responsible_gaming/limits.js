/**
 * Created by miguel on 10/02/2016.
 */
$(function () {
    function success(label, input) {
        input = $(input);
        var errorDiv = input.parents('.error-placer').find('.place');
        input.siblings('.warning-color').remove();
        errorDiv.hide();
    }
    function error(error, input) {
        input = $(input);
        input.siblings('.warning-color').remove();
        input.before('<i class="fa fa-times-circle warning-color"></i>');
        var errorDiv = input.parents('.error-placer').find('.place');
        errorDiv.text(error.text()).show();
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