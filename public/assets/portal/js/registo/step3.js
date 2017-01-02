var saveForm,
    saveButton;
    
$(function() {

    // validate signup form on keyup and submit
    $("#saveForm").validate({
        rules: {
            bank: "required",
            iban: {
                required: true,
                minlength: 21,
                maxlength: 21,
                digits: true
            },
            upload: {
                required: true
            }
        },
        messages: {
            bank: "Preencha o seu banco",
            iban: {
                required: "Preencha o seu Iban",
                minlength: "O Iban terá de ter 21 caracteres, excluíndo os primeiros dois dígitos PT50",
                maxlength: "O Iban terá de ter 21 caracteres, excluíndo os primeiros dois dígitos PT50",
                digits: "Apenas digitos são aceites"
            },
            upload: "Insira um comprovativo de Iban"
        }
    });

    var dpArea = $('#deposit_area');
    var tbArea = $('#deposit_tb');

    $('#saveForm input[name=payment_method]').on('change', function () {
        var checked = $('#method_tb').is(':checked');
        dpArea.toggle(!checked);
        tbArea.toggle(checked);
    });
    $('#info-close').click(function(){
        top.location.replace("/");
    });
});