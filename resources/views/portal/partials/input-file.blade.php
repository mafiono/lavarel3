<div class="box" id="box-{{$field}}">
    <div class="box__input">
        <svg class="box__icon" xmlns="http://www.w3.org/2000/svg" width="50" height="43" viewBox="0 0 50 43"><path d="M48.4 26.5c-.9 0-1.7.7-1.7 1.7v11.6h-43.3v-11.6c0-.9-.7-1.7-1.7-1.7s-1.7.7-1.7 1.7v13.2c0 .9.7 1.7 1.7 1.7h46.7c.9 0 1.7-.7 1.7-1.7v-13.2c0-1-.7-1.7-1.7-1.7zm-24.5 6.1c.3.3.8.5 1.2.5.4 0 .9-.2 1.2-.5l10-11.6c.7-.7.7-1.7 0-2.4s-1.7-.7-2.4 0l-7.1 8.3v-25.3c0-.9-.7-1.7-1.7-1.7s-1.7.7-1.7 1.7v25.3l-7.1-8.3c-.7-.7-1.7-.7-2.4 0s-.7 1.7 0 2.4l10 11.6z"/></svg>
        <input class="box__file" type="file" name="{{$field}}" id="{{$field}}" />
        <label for="{{$field}}"><strong>Clique para Enviar<br>{{$name}}</strong><span class="box__dragndrop"><br>ou arraste e solte neste espaço</span></label>
        <button class="box__button" type="submit">Enviar</button>
    </div>
    <div class="box__uploading">Uploading&hellip;</div>
    <div class="box__success">Concluído!</div>
    <div class="box__error">Erro! <span></span>.</div>
</div>
<!-- remove this if you use Modernizr -->
<script>(function(e,t,n){var r=e.querySelectorAll("html")[0];r.className=r.className.replace(/(^|\s)no-js(\s|$)/,"$1js$2")})(document,window,0);</script>
<script>
    'use strict';

    (function ($, window, document, undefined) {
        // feature detection for drag&drop upload

        var isAdvancedUpload = function () {
            var div = document.createElement('div');
            return ( ( 'draggable' in div ) || ( 'ondragstart' in div && 'ondrop' in div ) ) && 'FormData' in window && 'FileReader' in window;
        }();


        // applying the effect for every form
        var multiple = false;
        var autoSubmit = {{isset($autoSubmit) && $autoSubmit ? 'true':'false'}};
        $('#box-{{$field}}').each(function () {
            var $box = $(this),
                    $form = $box.parents('form'),
                    $input = $form.find('input[type="file"]'),
                    $label = $form.find('label'),
                    $errorMsg = $form.find('.box__error span'),
                    $restart = $form.find('.box__restart'),
                    droppedFiles = false,
                    showFiles = function (files) {
                        if (!files) return $label.text('');
                        var text = 'Selecione um ficheiro!';
                        if (files.length > 1) text = ( $input.attr('data-multiple-caption') || '' ).replace('{count}', files.length);
                        if (files.length === 1) text = files[0].name;
                        $label.text(text);
                    };

            // letting the server side to know we are going to make an Ajax request
            $form.append('<input type="hidden" name="ajax" value="1" />');

            // automatically submit the form on file select
            $input.on('change', function (e) {
                return validateFiles(e.target.files);
            });

            function validateFiles(files) {
                droppedFiles = $input.get(0).files = files;
                showFiles(files);
                if (autoSubmit) {
                    // automatically submit the form on file drop
                    $form.trigger( 'submit' );
                }
                return multiple ? files.length > 0 : files.length === 1;
            }

            // drag&drop files if the feature is available
            if (isAdvancedUpload) {
                $box
                    .addClass('has-advanced-upload'); // letting the CSS part to know drag&drop is supported by the browser
                $form
                    .on('drag dragstart dragend dragover dragenter dragleave drop', function (e) {
                        // preventing the unwanted behaviours
                        e.preventDefault();
                        e.stopPropagation();
                    })
                    .on('dragover dragenter', function () //
                    {
                        $box.addClass('is-dragover');
                    })
                    .on('dragleave dragend drop', function () {
                        $box.removeClass('is-dragover');
                    })
                    .on('drop', function (e) {
                        droppedFiles = e.originalEvent.dataTransfer.files; // the files that were dropped
                        return validateFiles(droppedFiles);
                    });
            }

            // if the form was submitted
            if (autoSubmit) {
                $form.on('submit', function (e) {
                    // preventing the duplicate submissions if the current one is in progress
                    if ($box.hasClass('is-uploading')) return false;

                    $box.addClass('is-uploading').removeClass('is-error');

                    if (isAdvancedUpload) // ajax file upload for modern browsers
                    {
                        e.preventDefault();

                        // gathering the form data
                        var ajaxData = new FormData($form.get(0));
                        if (droppedFiles) {
                            $.each(droppedFiles, function (i, file) {
                                ajaxData.append($input.attr('name'), file);
                            });
                        }

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
                                $box.removeClass('is-uploading');
                            },
                            success: function (data) {
                                $box.addClass(data.success ? 'is-success' : 'is-error');
                                if (data.success) {
                                    swal({
                                        title: 'Erro',
                                        text: data.success,
                                        type: 'success'
                                    }, function () {
                                        top.location.reload();
                                    });
                                }
                                if (data.error) {
                                    swal({
                                        title: 'Enviado com sucesso!',
                                        text: data.error,
                                        type: 'error'
                                    }, function () {
                                        $errorMsg.text('Erro ao enviar!');
                                    });
                                }
                            },
                            error: function () {
                                $.fn.popupError('Error. Please, contact the webmaster!');
                            }
                        });
                    }
                    else // fallback Ajax solution upload for older browsers
                    {
                        var iframeName = 'uploadiframe' + new Date().getTime(),
                                $iframe = $('<iframe name="' + iframeName + '" style="display: none;"></iframe>');

                        $('body').append($iframe);
                        $form.attr('target', iframeName);

                        $iframe.one('load', function () {
                            var data = $.parseJSON($iframe.contents().find('body').text());
                            $box.removeClass('is-uploading').addClass(data.success == true ? 'is-success' : 'is-error').removeAttr('target');
                            if (!data.success) $errorMsg.text(data.error);
                            $iframe.remove();
                        });
                    }
                });
            }

            // restart the form if has a state of error/success
            $restart.on('click', function (e) {
                e.preventDefault();
                $box.removeClass('is-error is-success');
                $input.trigger('click');
            });

            // Firefox focus bug fix for file input
            $input
                .on('focus', function () {
                    $input.addClass('has-focus');
                })
                .on('blur', function () {
                    $input.removeClass('has-focus');
                });
        });

    })(jQuery, window, document);

</script>