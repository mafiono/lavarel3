var auth = require('../../perfil/helpers/input-file');

module.exports.load = function () {
    auth.load();
    ga('send', {
        hitType: 'event',
        eventCategory: 'register',
        eventAction: 'step2-loaded-form',
        eventLabel: 'Step 2 Loaded'
    });
    // validate signup form on keyup and submit
    $("#saveForm").validate({
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
            upload: "required"
        },
        messages: {
            upload: "Por favor, introduza um comprovativo de identidade"
        }
    });

    $('#info-close').click(function(){
        top.location.replace("/");
    });
    $('#limpar').click(function(){
        document.location.href="/registar/step1";
    });
};
module.exports.unload = function () {
    auth.unload();
};