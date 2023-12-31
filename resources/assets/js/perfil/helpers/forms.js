import external from "../../external/autonumeric/autoNumeric";

var saveForm,
    layerBlock,
    objToExport = {},
    loaded = window.isformsLoaded;

if (!loaded) {
    (function () {
        let swalObj = swal;
        try {
            if (parent.swal && typeof parent.swal === 'function') {
                swalObj = parent.swal;
            }
        } catch (err) {
            // on diferent iframe
        }
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
                return swalObj(configs, callback);
            },
            closePopup: function () {
                return swal.close();
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
            obj.icon.after('<i class="cp-check-circle success-color"></i>');
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
            obj.icon.after('<i class="cp-exclamation-circle warning-color"></i>');
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
        function beforeSubmit(form) {
            $.fn.popup({
                title: 'Aguarde por favor!',
                type: 'warning',
                showCancelButton: false,
                showConfirmButton: false
            });
        }
        let retry = 0;
        objToExport.processResponse = function processResponse(response, $form, validator) {
            if (response === undefined) {
                // provide a fallback default
                response = {
                    status: 'error',
                    type: 'error',
                    msg: 'Ocorreu um erro inesperado. Por favor tente novamente.'
                };
            }
            if (validator && typeof validator.settings.customProcessStatus === 'function' &&
                validator.settings.customProcessStatus(response.status, response))
                return;
            if (response.token) {
                $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': response.token }});
                if ($form) {
                    $form.find('[name=_token]').val(response.token);
                }
                // retry
                if (retry++ < 3) {
                    $form.submit();
                    return false;
                }
            }
            retry = 0;
            function onPopupClose() {
                if (validator && typeof validator.settings.customPopupClose === 'function' &&
                    validator.settings.customPopupClose(arguments))
                    return;
                if(response.type == 'redirect') {
                    if (response.top) {
                        top.location.href = response.redirect;
                    } else if (!!response.force) {
                        window.location.href = response.redirect;
                    } else {
                        page(response.redirect);
                    }
                } else if (response.type == 'reload') {
                    page(page.current);
                } else if (response.type === 'refresh') {
                    top.location.reload();
                }
            }
            // console.log('Process Response', response);
            if (response.status) {
                switch (response.status) {
                    case 'success':
                        $.fn.popup({
                            title: response.title || '&nbsp;',
                            text: response.msg,
                            type: 'success',
                            allowEscapeKey: false
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
                                let firstError = null;
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
                                            firstError = input;
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
                                } else {
                                    $.fn.closePopup();
                                    if (firstError !== null) {
                                        firstError.focus();
                                    }
                                }
                            }
                        }
                        break;
                    default: {
                        $.fn.closePopup();
                        onPopupClose();
                        break;
                    }
                }
            }
            else if (response.success) {
                $.fn.popup({
                    title: 'Gravado com sucesso!',
                    text: response.success,
                    type: 'success'
                }, onPopupClose);
            }
            else if (response.error) {
                $.fn.popup({
                    title: 'Erro',
                    text: response.error,
                    type: 'error'
                }, onPopupClose);
            }
        };
        objToExport.submitHandler = function submitHandler(form, event) {
            var $form = $(form),
                validator = $form.data("validator");

            if ("function" === typeof validator.settings.beforeSubmit){
                validator.settings.beforeSubmit(form);
            }
            var ajaxData = new FormData($form.get(0));
            $form.find('[type=file]').each(function (index, input) {
                let $input = $(input);
                let droppedFiles = $input.data('files') || null;
                if (droppedFiles !== null && droppedFiles.length > 0) {
                    $.each(droppedFiles, function (i, file) {
                        ajaxData.append($input.attr('name'), file);
                    });
                }
            });

            // ajax request
            $.ajax({
                xhr: function() {
                    var xhr = new window.XMLHttpRequest();
                    var progressbar = null;

                    // Upload progress
                    xhr.upload.addEventListener("progress", function(evt){
                        if (progressbar === null) {
                            var tmp = $('.caspt .progress-bar');
                            if (tmp.length) {
                                progressbar = tmp;
                            }
                        }
                        if (evt.lengthComputable) {
                            var percentComplete = evt.loaded / evt.total;
                            if (progressbar) {
                                var val = parseInt(percentComplete * 100);
                                progressbar.width(val+ '%');
                                progressbar.attr('aria-valuenow', val);
                                progressbar.find('span').text(val + '% Completo');
                            }
                        }
                    }, false);

                    // Download progress
                    xhr.addEventListener("progress", function(evt){
                        if (evt.lengthComputable) {
                            var percentComplete = evt.loaded / evt.total;
                            if (progressbar) {
                                var val = parseInt(percentComplete * 100);
                                progressbar.width(val+ '%');
                                progressbar.attr('aria-valuenow', val);
                                progressbar.find('span').text(val + '% Completo');
                            }

                        }
                    }, false);

                    return xhr;
                },
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
        };

        var old = $.fn.validate;
        $.fn.validate = function (ops) {
            ops = $.extend({
                success: addSuccess,
                errorPlacement: addError,
                highlight: highlight,
                beforeSubmit: beforeSubmit,
                submitHandler: objToExport.submitHandler
            }, ops);
            return old.apply(this, [ops]);
        };
    })();

    $(function(){
        layerBlock = layerBlock || $('<div><div><i class="cp-spinner5 cp-spin"></i><span>Aguarde...</span></div></div>')
                .addClass('layer-loading-block')
                .hide().appendTo('body');

        var saveLoginForm = $('#saveLoginForm');
        saveLoginForm.validate({
            messages: {
                username: {
                    required: 'Preencha o campo de utilizador.',
                },
                password: {
                    required: 'Insira a sua palavra-passe.',
                }
            }
        });
        saveLoginForm.on('submit.validate', function () {
            var validator = saveLoginForm.validate(),
                errorMap = validator.errorMap,
                errorList = validator.errorList;
            if (errorList.length) {
                $.fn.popup({
                    type: 'error',
                    title: 'Verifique os dados p.f.!',
                    text: errorMap
                });
            }
        });
        if (typeof rules != 'undefined') {
            $("#saveForm").validate({
                rules: rules,
                messages: messages
            });
        }
    });
    let oldVal = $.validator.prototype.elementValue;
    $.validator.prototype.elementValue = function ($element) {
        if ($($element).data('autoNumeric') !== undefined) {
            return $($element).autoNumeric('get');
        } else {
            return oldVal.apply(this, arguments);
        }
    };
}

module.exports = objToExport;
window.isformsLoaded = true;
