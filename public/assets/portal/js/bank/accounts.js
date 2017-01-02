/**
 * Created by miguel on 02/02/2016.
 */
(function(){
    $("#add-account-form").validate({
        rules: {
            bank: {
                required: true,
                minlength: 3
            },
            iban: {
                required: true,
                iban: true
            }
        },
        messages: {
            bank: {
                required: "Indique o banco",
                minlength: "Indique o banco"
            },
            iban: {
                required: "Preencha o IBAN",
                iban: "O Iban terá de começar por PT50 e depois 21 caracteres"
            }
        }
    });

    $(".remove-account").on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();

        $form = $(this).parent('form');

        $.fn.popup({
            text: 'Tem a certeza que deseja remover esta conta?',
            type: 'error',
            confirmButtonText: 'Apagar',
            showCancelButton: true,
            closeOnConfirm: false,
            showLoaderOnConfirm: true
        }, function (confirmed) {
            if (confirmed) {
                $form.submit();
            }
        });
    });
})();