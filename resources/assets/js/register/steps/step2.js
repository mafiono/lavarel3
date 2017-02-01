var auth = require('../../perfil/helpers/input-file');

module.exports.load = function () {
    auth.load();
    // validate signup form on keyup and submit
    $("#saveForm").validate({
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