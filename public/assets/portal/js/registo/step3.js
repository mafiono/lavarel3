var saveForm,
    saveButton;
    
$(function() {

    // validate signup form on keyup and submit
    $("#saveForm").validate({
        success: function(label, input) {
            var registoClass = '.registo-form';
            input = $(input);
            if (input.prop('id') == 'cpostal1' || input.prop('id') == 'cpostal2' || input.prop('id') == 'pin_seguranca') {
                var registoClass = '.registo-form-costumized';
            }
            input.siblings('.success-color').remove();
            input.after('<i class="fa fa-check-circle success-color"></i>');
            input.siblings('.warning-color').remove();
        },
        errorPlacement: function(error, input) {
            var registoClass = '.registo-form';
            input = $(input);
            if (input.prop('id') == 'cpostal1' || input.prop('id') == 'cpostal2' || input.prop('id') == 'pin_seguranca') {
                var registoClass = '.registo-form-costumized';
            }
            input.siblings('.warning-color').remove();
            input.siblings('span').find('.warning-color').remove();
            input.after('<span><font class="warning-color">'+error.text()+'</font></span>')
            input.after('<i class="fa fa-times-circle warning-color"></i>');
            input.siblings('.success-color').remove();
        },
        rules: {
            bank: "required",
            iban: {
                required: true,
                minlength: 23,
                maxlength: 23,
                digits: true
            },
        },
        messages: {
            bank: "Preencha o seu banco",
            iban: {
                required: "Preencha o seu Iban",
                minlength: "O Iban terá de ter 23 caracteres, excluíndo os primeiros dois dígitos PT",
                maxlength: "O Iban terá de ter 23 caracteres, excluíndo os primeiros dois dígitos PT",
                digits: "Apenas digitos são aceites"
            }
        }
    });
});