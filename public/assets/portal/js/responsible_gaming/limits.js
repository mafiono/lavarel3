/**
 * Created by miguel on 10/02/2016.
 */
$(function () {
    $('.limites .number').autoNumeric("init",{
        aSep: ' ',
        aDec: ',',
        mDec: 0,
        vMin: '0'
    });
    // var old = $.validator.prototype.elementValue;
    $.validator.prototype.elementValue = function ($element) {
        // console.log($element);
        return $($element).autoNumeric('get');
    };

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
                min: 10
            },
            limit_weekly_deposit: {
                required: true,
                number: true,
                min: 10
            },
            limit_monthly_deposit: {
                required: true,
                number: true,
                min: 10
            }
        },
        messages: {
            limit_daily_deposit: {
                required: "Introduza limite diario",
                number: "Apenas dígitos são aceites no formato x.xx",
                min: "Por favor coloque um valor maior que 10."
            },
            limit_weekly_deposit: {
                required: "Introduza limite semanal",
                number: "Apenas dígitos são aceites no formato x.xx",
                min: "Por favor coloque um valor maior que 10."
            },
            limit_monthly_deposit: {
                required: "Introduza limite mensal",
                number: "Apenas dígitos são aceites no formato x.xx",
                min: "Por favor coloque um valor maior que 10."
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
                min: 10
            },
            limit_weekly_bet: {
                required: true,
                number: true,
                min: 10
            },
            limit_monthly_bet: {
                required: true,
                number: true,
                min: 10
            }
        },
        messages: {
            limit_daily_bet: {
                required: "Introduza limite diario",
                number: "Apenas dígitos são aceites no formato x.xx",
                min: "Por favor coloque um valor maior que 10."
            },
            limit_weekly_bet: {
                required: "Introduza limite semanal",
                number: "Apenas dígitos são aceites no formato x.xx",
                min: "Por favor coloque um valor maior que 10."
            },
            limit_monthly_bet: {
                required: "Introduza limite mensal",
                number: "Apenas dígitos são aceites no formato x.xx",
                min: "Por favor coloque um valor maior que 10."
            }
        }
    });
});