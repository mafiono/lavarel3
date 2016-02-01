var saveForm,
    saveButton;
    
$(function() {

    // validate signup form on keyup and submit
    $("#saveForm").validate({
        success: function(label, input) {
            var registoClass = '.registo-form';
            input = $(input);
            if (input.prop('id') == 'cpostal1' || input.prop('id') == 'cpostal2' || input.prop('id') == 'security_pin') {
                var registoClass = '.registo-form-costumized';
            }
            input.siblings('.success-color').remove();
            input.after('<i class="fa fa-check-circle success-color"></i>');
            input.siblings('.warning-color').remove();
        },
        errorPlacement: function(error, input) {
            var registoClass = '.registo-form';
            input = $(input);
            if (input.prop('id') == 'cpostal1' || input.prop('id') == 'cpostal2' || input.prop('id') == 'security_pin') {
                var registoClass = '.registo-form-costumized';
            }
            input.siblings('.warning-color').remove();
            input.siblings('span').find('.warning-color').remove();
            input.after('<span><font class="warning-color">'+error.text()+'</font></span>')
            input.after('<i class="fa fa-times-circle warning-color"></i>');
            input.siblings('.success-color').remove();
        },
        rules: {
            username: "required",
            email: {
                required: true,
                email: true
            },
            security_pin: true
        },
        messages: {
            username: "Preencha o seu nome de utilizador",
            email: {
                required: "Preencha o seu email",
                email: "Formato de email inválido",
            },
            security_pin: "Preencha o seu código pin"
        }
    });
});