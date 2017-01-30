// register only once
$.validator.methods.diffTo = function (value, element, param) {
    var ori = $('#' + param);
    return value !== ori.val();
};

module.exports.load = function () {
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
                minlength: 8,
                maxlength: 20,
                pattern: /^(?=.*[A-Z])(?=.*[0-9])(?=.*[a-z]).{8,20}$/,
                diffTo: 'old_password'
            },
            conf_password: {
                required: true,
                equalTo: "#password"
            }
        },
        messages: {
            old_password: {
                required: "Preencha a sua password actual"
            },
            password: {
                required: "Preencha a sua password",
                minlength: "Minimo 8 caracteres",
                maxlength: "Máximo 20 caracteres",
                pattern: "1 maiúscula, 1 minúscula e 1 numero",
                diffTo: "A nova password tem de ser diferente da actual"
            },
            conf_password: {
                required: "Confirme a sua password",
                equalTo: "Este campo tem de ser igual à sua nova password"
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
                required: "Preencha o seu PIN atual"
            },
            security_pin: {
                required: "Preencha o seu PIN",
                minlength: "O PIN tem de ter 4 numeros",
                maxlength: "O PIN tem de ter 4 numeros",
                diffTo: "O novo PIN tem de ser diferente do actual"
            },
            conf_security_pin: {
                required: "Confirme o seu PIN",
                equalTo: "Este campo tem de ser igual ao seu novo PIN"
            }
        }
    });
};
module.exports.unload = function () {
};