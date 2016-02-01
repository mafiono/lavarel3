var saveForm,
    saveButton;
    
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
        },
        errorPlacement: function(error, input) {
            var registoClass = '.registo-form';
            input = $(input);
            if (input.prop('id') == 'security_pin') {
                var registoClass = '.registo-form-costumized';
            }
            input.siblings('.warning-color').remove();
            input.siblings('span').find('.warning-color').remove();
            input.after('<span><font class="warning-color">'+error.text()+'</font></span>')
            input.after('<i class="fa fa-times-circle warning-color"></i>');
            input.siblings('.success-color').remove();
        },
        rules: {
            name: "required",
            nationality: "required",
            document_number: {
                required: true
            },
            tax_number: {
                required: true,
                minlength: 9,
                maxlength: 9,
                digits: true
            },
            profession: "required",
            address: "required",
            city: "required",
            zip_code: {
                required: true,
                minlength: 4
            },
            email: {
                required: true,
                email: true
            },
            conf_email: {
                required: true,
                email: true,
                equalTo: "#email"
            }, 
            phone: {
                required: true,
                digits: true
            },
            username: "required",
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
                minlength: 5
            },
            general_conditions: "required"
        },
        messages: {
            name: "Preencha o seu nome",
            nationality: "Preencha a sua nacionalidade",
            document_number: {
                required: "Preencha a sua identificação",
                minlength: "A identificação civíl terá de ter 9 digitos",
                maxlength: "A identificação civíl terá de ter 9 digitos",
                digits: "Apenas digitos são aceites"
            },
            tax_number: {
                required: "Preencha o seu NIF",
                minlength: "O NIF terá de ter 9 digitos",
                maxlength: "O NIF terá de ter 9 digitos",
                digits: "Apenas digitos são aceites"                    
            },
            profession: "Preencha a sua profissão",
            address: "Preencha a sua morada",
            city: "Preencha a sua cidade",
            zip_code: {
                required: "Preencha o seu código postal",
                minlength: "Este campo terá de ter no mínimo 4 digitos"            
            },
            email: {
                required: "Preencha o seu email",
                email: "Insira um email válido"
            },
            conf_email: {
                required: "Confirme o seu email",
                email: "Insira um email válido",
                equalTo: "Este campo tem de ser igual ao seu email"
            },
            phone: {
                required: "Preencha o seu telefone",
                digits: "Apenas digitos são aceites"
            },
            username: "Preencha o seu nome utilizador",
            password: {
                required: "Preencha a sua password",
                minlength: "A password tem de ter pelo menos 6 caracteres"
            },                
            conf_password: {
                required: "Confirme a sua password",
                minlength: "A password tem de ter pelo menos 6 caracteres",
                equalTo: "Este campo tem de ser igual à sua password"
            },
            security_pin: {
                required: "Preencha o seu código de segurança",
                minlength: "O código de segurança tem de ter pelo menos 5 caracteres"
            },                
            general_conditions: "Tem de aceitar os Termos e Condições e Regras"
        }
    });
});