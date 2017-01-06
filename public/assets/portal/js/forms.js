var saveForm,
    layerBlock;

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
                cancelButtonText: "Cancelar",
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
                return parent.swal(configs, callback);
            } else {
                return swal(configs, callback);
            }
        }
    });
    function processResponse(response, $form, validator) {
        if (validator && typeof validator.settings.customProcessStatus === 'function' &&
            validator.settings.customProcessStatus(response.status, response))
            return;

        function onPopupClose() {
            if(response.type == 'redirect'){
                if (response.top) {
                    top.location.href = response.redirect;
                } else {
                    window.location.href = response.redirect;
                }
            } else if(response.type == 'reload'){
                window.location.reload();
            }
        }
        console.log('Process Response', response);
        if (response.status) {
            switch (response.status) {
                case 'success':
                    $.fn.popup({
                        title: response.title || '&nbsp;',
                        text: response.msg,
                        type: 'success'
                    }, onPopupClose);
                    break;
                case 'error':
                    // TODO Clean this logic.
                    if((response.type == 'error' || response.type == 'login_error')
                        && typeof response.msg === "string"){
                        $.fn.popup({
                            title: response.title || '&nbsp;',
                            text: response.msg,
                            type: 'error'
                        }, onPopupClose);
                    } else {
                        if (typeof response.msg === "string") {
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
                    }
                    break;
                default: onPopupClose(); break;
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

    var saveLoginForm = $('#saveLoginForm').validate();
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



