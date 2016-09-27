
$(function() {

    // validate signup form on keyup and submit
    $("#saveForm").validate({
        success: function(label, input) {
            var registoClass = '.registo-form';
            input = $(input);
            if (input.prop('id') == 'security_pin') {
                var registoClass = '.registo-form-costumized';
            }
            input.siblings('.success-color').remove();
            input.after('<i class="fa fa-check-circle success-color"></i>');
            input.siblings('.warning-color').remove();
            input.parent().removeClass('error');
        },
        errorPlacement: function(error, input) {
            var registoClass = '.registo-form';
            input = $(input);
            if (input.prop('id') == 'security_pin') {
                var registoClass = '.registo-form-costumized';
            }
            input.siblings('.warning-color').remove();
            input.siblings('span').remove();
            input.after('<span><font class="texto-erro">'+error.text()+'</font></span>')
            input.after('<i class="fa fa-exclamation-circle warning-color"></i>');
            input.siblings('.success-color').remove();
            input.parent().addClass('error');
        },
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
                digits: true
            },
            country: {
                required: true

            },
            sitprofession: {
                required: true
            },
            profession: {
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
                telephone: true,
                minlength:6

            },
            mobile:{
                telephone:true
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
            general_conditions: "required"
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
            profession: {
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
                telephone: "Coloque o indicativo pf.",
                minlength:"Por favor, verifique o número"
            },
            mobile: {
                telephone: "Coloque o indicativo pf.",
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
                minlength: "4 Caracteres",
                maxlength: "4 Caracteres"
            },
            general_conditions: " "
        }
    });

    var sitProf = $('#sitprofession select');
    var prof = $('#profession');
    Rx.Observable.fromEvent(sitProf, 'change')
        .map(function(e){
            return sitProf.val();
        })
        .map(function(x){
            switch (x){
                case "11":
                case "22":
                case "33":
                case "66":
                case "99":
                    return true;
                case "44":
                case "55":
                case "77":
                case "88":
                default:
                    return false;
            }
        })
        .distinctUntilChanged()
        .merge(Rx.Observable.of(false))
        .subscribe(function(x){
            if (x) {
                prof.rules('add', {
                    required: true
                });
            } else {
                prof.rules('remove', 'required');
            }
            prof.parent().toggle(x);
        });
    prof.parent().removeClass('hidden');
});