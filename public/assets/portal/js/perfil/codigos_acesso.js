(function () {
    function suss(label, input) {
        input = $(input);
        input.siblings('i').remove();
        input.siblings('span').remove();
        input.after('<i class="fa fa-check-circle success-color"></i>');
    }

    function err(error, input) {
        input = $(input);
        input.removeClass('error');
        input.siblings('i').remove();
        input.siblings('span').remove();
        input.after('<i class="fa fa-exclamation-circle warning-color"></i>');
        input.after('<span><font class="warning-color">'+error.text()+'</font></span>')
    }
    $.validator.methods.diffTo = function (value, element, param) {
        var ori = $('#' + param);
        return value !== ori.val();
    };

    var formPass = $("#saveFormPass");
    formPass.validate({
        success: suss,
        errorPlacement: err,
        rules: {
            old_password: {
                required: true
            },
            password: {
                required: true,
                minlength: 6,
                diffTo: 'old_password'
            },
            conf_password: {
                required: true,
                minlength: 6,
                equalTo: "#password"
            }
        },
        messages: {
            old_password: {
                required: "Preencha a sua antiga password"
            },
            password: {
                required: "Preencha a sua password",
                minlength: "A password tem de ter pelo menos 6 caracteres",
                diffTo: "A nova password tem de ser diferente da actual"
            },
            conf_password: {
                required: "Confirme a sua password",
                minlength: "A password tem de ter pelo menos 6 caracteres",
                equalTo: "Este campo tem de ser igual à sua password"
            }
        }
    });
    var formPin = $("#saveFormPin");
    formPin.validate({
        success: suss,
        errorPlacement: err,
        rules: {
            old_security_pin: {
                required: true
            },
            security_pin: {
                required: true,
                minlength: 4,
                maxlength: 4,
                diffTo: 'old_security_pin'
            },
            conf_security_pin: {
                required: true,
                equalTo: "#security_pin"
            }
        },
        messages: {
            old_security_pin: {
                required: "Preencha o seu código de segurança antigo"
            },
            security_pin: {
                required: "Preencha o seu código de segurança",
                minlength: "O código de segurança tem de ter 4 numeros",
                maxlength: "O código de segurança tem de ter 4 numeros",
                diffTo: "O novo código tem de ser diferente do actual"
            },
            conf_security_pin: {
                required: "Confirme o seu código de segurança",
                equalTo: "Este campo tem de ser igual ao seu código de segurança"
            }
        }
    });
})();