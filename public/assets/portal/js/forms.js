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
            var validator = formElement.data("validator");
            enableFormSubmit();
            if (validator && typeof validator.settings.customProcessStatus === 'function' &&
                validator.settings.customProcessStatus(response.status, response))
                return;
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
            } else if(response.status == 'success'){
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

(function () {
    $.extend($.fn, {
        /**
         * Creates a generic Popup
         * @param configs
         * @param callback
         */
        popup: function (configs, callback) {
            configs = $.extend({
                title: '&nbsp;',
                text: '&nbsp;',
                type: 'success',
                html: true,
                customClass: 'caspt'
            }, configs);
            if (typeof configs.text !== 'string') {
                var text = '';
                $.each(configs.text, function (idx, item) {
                    if (item && item.length > 0)
                        text += item + '<br>';
                });
                configs.text = text;
            }
            if (parent.swal && typeof parent.swal === 'function') {
                parent.swal(configs, callback);
            } else {
                swal(configs, callback);
            }
        }
    });
    function processResponse(response, $form, validator) {
        if (validator && typeof validator.settings.customProcessStatus === 'function' &&
            validator.settings.customProcessStatus(response.status, response))
            return;

        function onPopupClose() {
            if(response.type == 'redirect'){
                window.location.href = response.redirect;
            } else if(response.type == 'reload'){
                window.location.reload();
            }
        }
        console.log('Process Response', response);
        if (response.status) {
            switch (response.status) {
                case 'success':
                    if(response.type == 'redirect'){
                        window.location.href = response.redirect;
                    }else if(response.type == 'reload'){
                        window.location.reload();
                    }else{
                        $.fn.popup({
                            title: response.title || '&nbsp;',
                            text: response.msg,
                            type: 'success'
                        }, onPopupClose);
                    }
                    break;
                case 'error':
                    if(response.type == 'error' || response.type == 'login_error'){
                        $.fn.popup({
                            title: response.title || '&nbsp;',
                            text: response.msg,
                            type: 'error'
                        }, onPopupClose);
                    } else {
                        var missingFields = {};
                        $.each(response.msg, function(i, msg){
                            if(msg.length > 0){
                                var input = $form.find('#'+i);
                                if (input.length){
                                    input.parent().children('span.error').text(msg).show();
                                } else {
                                    missingFields[i] = msg;
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
                        if (!$.isEmptyObject(missingFields)) {
                            $.fn.popup({
                                title: response.title || '&nbsp;',
                                text: missingFields,
                                type: 'error'
                            }, onPopupClose);
                        }
                    }
                    break;
                default: break;
            }
        } else if (response.success) {
            $.fn.popup({
                title: 'Gravado com sucesso!',
                text: response.success,
                type: 'success'
            }, onPopupClose);
        } else if (response.error) {
            $.fn.popup({
                title: 'Erro',
                text: response.error,
                type: 'error'
            }, onPopupClose);
        }
    }

    var old = $.fn.validate;
    $.fn.validate = function (ops) {
        ops = $.extend({
            submitHandler: function (form, event) {
                var $form = $(form),
                    validator = this;

                var ajaxData = new FormData($form.get(0));

                // ajax request
                $.ajax({
                    url: $form.attr('action'),
                    type: $form.attr('method'),
                    data: ajaxData,
                    dataType: 'json',
                    cache: false,
                    contentType: false,
                    processData: false,
                    complete: function () {
                        console.log('complete');
                    },
                    success: function (data) {
                        return processResponse(data, $form, validator);
                    },
                    error: function (obj, type, name) {
                        return processResponse(obj.responseJSON, $form, validator);
                    }
                });
            }
        }, ops);
        return old.apply(this, [ops]);
    };
})();

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
                input.after('<span><font class="warning-color">'+error.text()+'</font></span>');
                input.after('<i class="fa fa-exclamation-circle warning-color"></i>');
                input.siblings('.success-color').remove();
            },
            rules: rules,
            messages: messages
        });
    }
});



