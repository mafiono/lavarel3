
$(function() {

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
                maxlength: 15,

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
            address: "required",
            city: "required",
            zip_code: {
                required: true,
                minlength: 4
            },
            email: {
                required: true,
                email: true,
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
                maxlength:15
            },
            username: {
                required: true,
                remote: {
                    url: '/api/check-users',
                    type: 'post'
                }
            },
            password: {
                required: true,
                minlength: 6
            },
            conf_password: {
                required: true,
                minlength: 6,
                equalTo: "#password"
            },
            security_pin: {
                required: true,
                minlength: 4,
                maxlength: 4
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
                maxlength: "Máximo 15 caracteres",
                digits: "Apenas digitos são aceites"
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
            address: "Por favor, verifique os dados",
            city: "Por favor, verifique os dados",
            zip_code: {
                required: "Por favor, verifique os dados",
                minlength: "Mínimo 4 digitos"
            },
            email: {
                required: "Por favor, verifique os dados",
                email: "Insira um email válido"
            },
            conf_email: {
                required: "Por favor, verifique os dados",
                email: "Insira um email válido",
                equalTo: "Confirme o seu email"
            },
            phone: {
                required: "Por favor, verifique os dados",
                maxlength: "Por favor, verifique o número",
                minlength:"Por favor, verifique o número"
            },
            username: {
                required: "Por favor, verifique os dados"
            },
            password: {
                required: "Por favor, verifique os dados",
                minlength: "Mínimo 6 caracteres"
            },
            conf_password: {
                required: "Por favor, verifique os dados",
                minlength: "Mínimo 6 caracteres",
                equalTo: "Tem de ser igual à sua password"
            },
            security_pin: {
                required: "Por favor, verifique os dados",
                minlength: "4 numeros",
                maxlength: "4 numeros"
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

    $('#info-close').click(function(){
        top.location.replace("/");
    });

    $('#limpar').click(function(){
        $('#saveForm')[0].reset();
    });
});