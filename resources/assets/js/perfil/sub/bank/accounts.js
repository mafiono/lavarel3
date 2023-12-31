var auth = require('../../helpers/input-file'),
    forms = require('../../helpers/forms');

module.exports.load = function(){
    auth.load();
    $("#add-account-form").validate({
        beforeSubmit: function beforeSubmit(form) {
            $.fn.popup({
                title: 'Aguarde por favor!',
                type: 'warning',
                text: '<div class="bs-wp"><div class="progress">\
                    <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0%">\
                        <span class="sr-only">0% Completo</span>\
                    </div>\
                </div></div>',
                html: true,
                showCancelButton: false,
                showConfirmButton: false
            });
        },
        rules: {
            bank: {
                required: true,
                minlength: 3
            },
            bic: {
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
                required: 'Obrigatório',
                minlength: 'Minimo 3'
            },
            bic: {
                required: 'Obrigatório',
                minlength: 'Minimo 3'
            },
            iban: {
                required: 'Preencha o IBAN',
                iban: 'Introduza um IBAN válido'
            }
        }
    });

    $('.bank-account i.edit-account').on('click', function (e) {
        e.preventDefault();
        e.stopPropagation();

        let tr = $(this).parents('tr');
        $('#bank').val(tr.find('td:nth(0)').text().trim());
        $('#bic').val(tr.find('td:nth(1)').text().trim());
        $('#iban').val(tr.find('td:nth(2)').text().trim());
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