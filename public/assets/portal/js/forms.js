var formId,
    formSubmit,
    formSubmitText,
    saveForm,
    saveButton,
    layerBlock;

function validateInput(input)
{
    var registoClass = '.registo-form';
    if ($(registoClass).length == 0)
        registoClass = '.form-group';

    if (input.prop('id') == 'pin_seguranca') {
        registoClass = '.registo-form-costumized';
    }

    var valid = true;

    if(input.is('input') || input.is('textarea')){
        valid = valid && input.val().length;

        if(input.val().length){
            input.parents(registoClass).removeClass('has-error required');
            input.parents(registoClass).find('.warning-color').hide();
            input.parents(registoClass).find('.success-color').show();
        }
        else{
            input.parents(registoClass).addClass('has-error required');
            input.parents(registoClass).find('.success-color').hide();
            input.parents(registoClass).find('.warning-color').show();    
        }
    }else if(input.is('select')){
        valid = valid && input.val();
        if(input.val() != null){
            input.parents(registoClass).removeClass('has-error required');
            input.parents(registoClass).find('.warning-color').hide();
            input.parents(registoClass).find('.success-color').show();
        }else{
            input.parents(registoClass).addClass('has-error required');
            input.parents(registoClass).find('.success-color').hide();
            input.parents(registoClass).find('.warning-color').show();                    
        }
    } 

    return valid;   
}

function isValidForm(form){

    var valid = true;

    form.find('input.required,textarea.required,select.required').each(function(i, input){
        input = $(input);
        valid = validateInput(input);
    });

    return valid;
}


function onFormSubmit(formElement){
    var validator = formElement.data("validator");
    formElement.ajaxForm({
        beforeSubmit: function(arr, $form, options) {

            if(!isValidForm(formElement)){
                return false;
            }

            formElement.find('.error').hide();
            disableFormSubmit();
        },
        error: function(ex){
            enableFormSubmit();
            if (ex && ex.responseText == 'Invalid security token.'){
                location.reload();
            }
        },
        success: function(response){
            enableFormSubmit();
            if(response.status == 'error'){
                if(response.type == 'error'){
                    $('.alert.alert-danger .msg').html(response.msg);
                    $('.alert.alert-danger').show();
                }else if(response.type == 'login_error'){
                    //$('.login-error').text(response.msg);
                    //$('.login-error').show();
                    alert(response.msg);
                    $("#user-login").focus();
                }else{
                    $.each(response.msg, function(i, msg){
                        if(msg.length > 0){
                            var input = formElement.find('#'+i);
                            if (input.length){
                                input.parent().children('span.error').text(msg).show();
                            }
                            validator.invalid[i] = true;
                            validator.errorMap[i] = msg;
                            validator.errorList.push({
                                element: input,
                                message: msg,
                                method: ''
                            });
                        }
                    });
                    validator.showErrors(response.msg);
                }
            }else if(response.status == 'success'){
                if(response.type == 'redirect'){
                    window.location.href = response.redirect;
                }else if(response.type == 'reload'){
                    window.location.reload();
                }else{
                    $('.alert.alert-success .msg').html(response.msg);
                    $('.alert.alert-success').show();                         
                }
            }
        }
    });
}

function disableFormSubmit()
{
    formSubmitText = formSubmit.text();
    formSubmit.text('Aguarde...');
    formSubmit.prop('disabled', true);
    layerBlock.show();
}

function enableFormSubmit()
{
    layerBlock.hide();
    formSubmit.text(formSubmitText);    
    formSubmit.prop('disabled', false);
}

$(function(){
    layerBlock = layerBlock || $('<div><div><i class="fa fa-spinner fa-spin"></i><span>Aguarde...</span></div></div>')
            .addClass('layer-loading-block')
            .hide().appendTo('body');
    formId = $('#saveForm');
    formSubmit = formId.find('.formSubmit');
    formSubmit.on('click', function(){
        onFormSubmit(formId);
    });

    var saveLoginForm = $('#saveLoginForm');
    var formLoginSubmit = saveLoginForm.find('.formLoginSubmit');
    formLoginSubmit.on('click', function(){
        onFormSubmit(saveLoginForm);
    });

    var resetPassForm = $('#resetPassForm');
    var resetPassSubmit = resetPassForm.find('.formSubmit');
    resetPassSubmit.on('click', function(){
        onFormSubmit(resetPassForm);
    });

    if (typeof rules != 'undefined') {
        $("#saveForm").validate({
            success: function(label, input) {
                var registoClass = '.registo-form';
                input = $(input);
                if (input.prop('id') == 'security_pin') {
                    var registoClass = '.registo-form-costumized';
                }
                input.siblings('.success-color').remove();
                input.after('<i class="fa fa-check-circle success-color"></i>');
                input.siblings('.warning-color').remove();
            },
            errorPlacement: function(error, input) {
                var registoClass = '.registo-form';
                input = $(input);
                if (input.prop('id') == 'security_pin') {
                    var registoClass = '.registo-form-costumized';
                }
                input.siblings('.warning-color').remove();
                input.siblings('span').find('.warning-color').remove();
                input.after('<span><font class="warning-color">'+error.text()+'</font></span>')
                input.after('<i class="fa fa-times-circle warning-color"></i>');
                input.siblings('.success-color').remove();
            },
            rules: rules,
            messages: messages
        });
    }

});



