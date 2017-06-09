var auth = require('../helpers/input-file'),
    forms = require('../helpers/forms');

module.exports.load = function () {
    auth.load();
    $('.docs .delete').click(function (e) {
        e.preventDefault();
        e.stopPropagation();
        var link = $(this).attr('href');
        $.ajax({
            url: link,
            type: 'get',
            dataType: 'json',
            success: function (data) {
                return forms.processResponse(data);
            },
            error: function (obj, type, name) {
                return forms.processResponse(obj.responseJSON);
            }
        });
    });

    var obj = {
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
        customProcessStatus: function (status, response) {
            // reload page
            page(page.current);
            return false;
        }
    };
    $('#saveIdentityForm').validate(obj);
    $('#saveAddressForm').validate(obj);
};
module.exports.unload = function () {
    auth.unload();
};