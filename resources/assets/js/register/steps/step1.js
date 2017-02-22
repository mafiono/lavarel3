
module.exports.load = function () {
    // validate signup form on keyup and submit
    $("#saveForm").validate({
        rules: {
            gender: "required",
            name: "required",
            nationality:  {
                required: true

            },
            document_number: {
                required: true,
                minlength: 6,
                maxlength: 13,
                remote: {
                    url: '/api/check-users',
                    type: 'post'
                }
            },
            tax_number: {
                required: true,
                minlength: 9,
                maxlength: 9,
                digits: true,
                remote: {
                    url: '/api/check-users',
                    type: 'post'
                }
            },
            country: {
                required: true
            },
            sitprofession: {
                required: true
            },
            address: {
                required: true,
                maxlength: 150,
            },
            city: "required",
            zip_code: {
                required: true,
                pattern: /^[0-9]{4}-[0-9]{3}$/
            },
            email: {
                required: true,
                email: true,
                maxlength: 100,
                remote: {
                    url: '/api/check-users',
                    type: 'post'
                }
            },
            conf_email: {
                required: true,
                email: true,
                equalTo: "#email"
            }, 
            phone: {
                required: true,
                minlength:6,
                maxlength:15,
                pattern: /^\+[0-9]{2,3}\s*[0-9]{6,11}$/
            },
            username: {
                required: true,
                minlength: 5,
                maxlength: 45,
                pattern: /^(?![_.])(?!.*[_.]{2})[a-zA-Z0-9._]+(?![_.])$/,
                remote: {
                    url: '/api/check-users',
                    type: 'post'
                }
            },
            password: {
                required: true,
                minlength: 8,
                maxlength: 20,
                pattern: /^(?=.*[A-Z])(?=.*[0-9])(?=.*[a-z]).{8,20}$/
            },
            conf_password: {
                required: true,
                equalTo: "#password"
            },
            security_pin: {
                required: true,
                pattern: /^[0-9]{4}$/
            },
            general_conditions: "required",
            bank_name: null,
            bank_bic: null,
            bank_iban: {
                iban: true
            },
            captcha: {
                required: true,
                minlength: 5,
                maxlength: 5
            }
        },
        messages: {
            gender: " ",
            name: "Por favor, verifique os dados",
            firstname: "Por favor, verifique os dados",
            nationality: "Por favor, verifique os dados",
            country: "Por favor, verifique os dados",
            document_number: {
                required: "Por favor, verifique os dados",
                minlength: "Mínimo 6 caracteres",
                maxlength: "Máximo 13 caracteres",
            },
            tax_number: {
                required: "Por favor, verifique os dados",
                minlength: "Número incorrecto",
                maxlength: "Número incorrecto",
                digits: "Apenas digitos"
            },
            sitprofession: {
                required: "Por favor, verifique os dados"
            },
            address: {
                required: "Por favor, verifique os dados",
                maxlength: 'Máximo de 150 caracteres'
            },
            city: "Por favor, verifique os dados",
            zip_code: {
                required: "Por favor, verifique os dados",
                pattern: "Formato XXXX-XXX"
            },
            email: {
                required: "Por favor, verifique os dados",
                email: "Insira um email válido",
                maxlength: "Máximo de 100 caracteres",
            },
            conf_email: {
                required: "Por favor, verifique os dados",
                email: "Insira um email válido",
                equalTo: "Confirme o seu email"
            },
            phone: {
                required: "Por favor, verifique os dados",
                maxlength: "Minimo de 6 números",
                minlength: "Maximo de 14 numeros",
                pattern:"Formato +351 xxxxxxxxx"
            },
            username: {
                required: "Por favor, verifique os dados",
                minlength: "Minimo 4 caracteres",
                pattern: "Formato inválido",
            },
            password: {
                required: "Por favor, verifique os dados",
                minlength: "Minimo 8 caracteres",
                maxlength: "Máximo 20 caracteres",
                pattern: "1 maiúscula, 1 minúscula e 1 numero"
            },
            conf_password: {
                required: "Por favor, verifique os dados",
                equalTo: "Tem de ser igual à sua password"
            },
            security_pin: {
                required: "Por favor, verifique os dados",
                pattern: "4 numeros"
            },
            general_conditions: " ",
            bank_name: '',
            bank_bic: '',
            bank_iban: {
                iban: 'Introduza um IBAN válido'
            },
            captcha: {
                required: 'Introduza o valor do captcha',
                minlength: '5 caracteres',
                maxlength: '5 caracteres'
            }
        }
    });

    $('#captcha-refresh').click(function (e) {
        e.preventDefault();
        e.stopPropagation();
        var url = '/captcha?_CAPTCHA&refresh=1&t=' + new Date().getTime();
        $('#captcha-img').attr('src', url);
    });

    $('#register-close').click(function(){
        page("/");
    });

    $('#limpar').click(function(){
        $('#saveForm')[0].reset();
    });
};
module.exports.unload = function () {

};