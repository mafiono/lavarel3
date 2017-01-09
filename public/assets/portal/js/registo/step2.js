
$(function() {

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
});