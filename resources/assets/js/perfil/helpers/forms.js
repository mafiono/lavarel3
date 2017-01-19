var saveForm,
    layerBlock,
    objToExport = {},
    loaded = window.isformsLoaded;

if (!loaded) {
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
        function cleanMsgs(i) {
            var input = $(i), place = input.parent(), icon = input, areaField = input.parents('.error-placer');
            if (areaField.length === 0) {
                areaField = place;
            }
            place = areaField.find('.place');
            place = place.length > 0 ? place: areaField;
            icon = areaField.find('.place-i');
            icon = icon.length > 0 ? icon: input;
            areaField.find('.success-color, .warning-color, span.error').remove();
            areaField.removeClass('error').removeClass('success');
            return {
                area: areaField,
                input: input,
                place: place,
                icon: icon,
                isEmpty: input.length === 0,
                showIcon: !(areaField.hasClass('no-icon') || areaField.hasClass('no-error')),
                showMsg: !areaField.hasClass('no-error')
            };
        }
        function addSuccess(label, input) {
            var obj = cleanMsgs(input);
            obj.area.toggleClass('success', true);
            if (!obj.showIcon) return;
            obj.icon.after('<i class="fa fa-check-circle success-color"></i>');
        }
        function addError(error, input) {
            var obj = cleanMsgs(input);
            obj.area.toggleClass('error', true);
            if (!obj.showMsg) return false;
            if (obj.isEmpty) return false;
            if (typeof error === 'object' && typeof error.text === 'function') error = error.text();
            if (Array.isArray(error) && error.length) error = error[0];
            obj.place.append('<span class="error warning-color">'+error+'</span>');
            if (!obj.showIcon) return true;
            obj.icon.after('<i class="fa fa-exclamation-circle warning-color"></i>');
            return true;
        }
        function highlight(element, errorClass, validClass) {
            if (element === undefined) return;
            if (element.type === "radio" ) {
                this.findByName( element.name ).addClass( errorClass ).removeClass( validClass );
            } else {
                $( element ).addClass( errorClass ).removeClass( validClass );
            }
        }
        objToExport.processResponse = function processResponse(response, $form, validator) {
            if (validator && typeof validator.settings.customProcessStatus === 'function' &&
                validator.settings.customProcessStatus(response.status, response))
                return;

            function onPopupClose() {
                if(response.type == 'redirect'){
                    if (response.top) {
                        top.location.href = response.redirect;
                    } else {
                        page(response.redirect);
                    }
                } else if(response.type == 'reload'){
                    page(page.current);
                }
            }
            // console.log('Process Response', response);
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
                                        if (!addError(msg, input)) {
                                            missingFields[i] = msg;
                                        }
                                        if (input.length) {
                                            validator.invalid[i] = true;
                                            validator.errorMap[i] = msg;
                                            validator.errorList.push({
                                                element: input,
                                                message: msg,
                                                method: ''
                                            });
                                        }
                                    }
                                });
                                // confirm if its the same
                                validator.showErrors(validator.errorMap);
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
        };

        var old = $.fn.validate;
        $.fn.validate = function (ops) {
            ops = $.extend({
                success: addSuccess,
                errorPlacement: addError,
                highlight: highlight,
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
                            // console.log('complete');
                        },
                        success: function (data) {
                            return objToExport.processResponse(data, $form, validator);
                        },
                        error: function (obj, type, name) {
                            return objToExport.processResponse(obj.responseJSON, $form, validator);
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
                rules: rules,
                messages: messages
            });
        }
    });
}

module.exports = objToExport;
window.isformsLoaded = true;
