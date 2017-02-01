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