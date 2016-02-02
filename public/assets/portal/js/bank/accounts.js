/**
 * Created by miguel on 02/02/2016.
 */
(function(){
    $("#add-account-form").validate({
        success: function(label, input) {
            input = $(input);
            input.siblings('.success-color').remove();
            input.after('<i class="fa fa-check-circle success-color"></i>');
            input.siblings('.warning-color').remove();
        },
        errorPlacement: function(error, input) {
            input = $(input);
            input.siblings('.warning-color').remove();
            input.siblings('span').find('.warning-color').remove();
            input.after('<span><font class="warning-color">'+error.text()+'</font></span>')
            input.after('<i class="fa fa-times-circle warning-color"></i>');
            input.siblings('.success-color').remove();
        },
        rules: {
            bank: {
                required: true,
                minlength: 3
            },
            iban: {
                required: true,
                minlength: 21,
                maxlength: 21,
                digits: true
            }
        },
        messages: {
            bank: {
                required: "Indique o banco",
                minlength: "Indique o banco"
            },
            iban: {
                required: "Preencha o IBAN",
                minlength: "O Iban terá de ter 21 caracteres, excluíndo os primeiros dois dígitos PT50",
                maxlength: "O Iban terá de ter 21 caracteres, excluíndo os primeiros dois dígitos PT50",
                digits: "Apenas digitos são aceites"
            }
        }
    });

    $("#add-account-btn").on('click', function() {
        $(this).addClass("settings-button-selected");
        $("#select-account-btn").removeClass("settings-button-selected");
        $("#add-account-container").show();
        $("#select-account-container").hide();
    });

    $("#select-account-btn").on('click', function() {
        $(this).addClass("settings-button-selected");
        $("#add-account-btn").removeClass("settings-button-selected");
        $("#add-account-container").hide();
        $("#select-account-container").show();
    });

    $(".remove-account").on('click', function(e) {
        e.preventDefault();

        var confirmed = confirm('Tem a certeza que deseja remover esta conta?');
        if (confirmed) {
            $(this).parent('form').submit();
        }
    });
})();