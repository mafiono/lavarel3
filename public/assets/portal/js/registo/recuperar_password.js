var saveForm,
    saveButton;
    
$(function() {

    // validate signup form on keyup and submit
    $("#saveForm").validate({
        rules: {
            username: "required",
            email: {
                required: true,
                email: true
            },
            security_pin: true
        },
        messages: {
            username: "Preencha o seu nome de utilizador",
            email: {
                required: "Preencha o seu email",
                email: "Formato de email inválido",
            },
            security_pin: "Preencha o seu código pin"
        }
    });
});