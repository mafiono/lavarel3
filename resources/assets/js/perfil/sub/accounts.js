var auth = require('../helpers/input-file'),
    forms = require('../helpers/forms');

module.exports.load = function(){
    auth.load();
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

        $url = $(this).attr('href');

        $.fn.popup({
            text: 'Tem a certeza que deseja remover esta conta?',
            type: 'error',
            confirmButtonText: 'Apagar',
            showCancelButton: true,
            closeOnConfirm: false,
            showLoaderOnConfirm: true
        }, function (confirmed) {
            if (confirmed) {
                $.ajax({
                    url: $url,
                    type: 'delete',
                    dataType: 'json',
                    success: function (data) {
                        return forms.processResponse(data);
                    },
                    error: function (obj, type, name) {
                        return forms.processResponse(obj.responseJSON);
                    }
                });
            }
        });
    });
};
module.exports.unload = function () {
    auth.unload();
};