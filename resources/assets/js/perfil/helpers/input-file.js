module.exports.load = function () {

    var isAdvancedUpload = function () {
        var div = document.createElement('div');
        return ( ( 'draggable' in div ) || ( 'ondragstart' in div && 'ondrop' in div ) ) && 'FormData' in window && 'FileReader' in window;
    }();

    // applying the effect for every form
    var multiple = false;
    $('.box.input-file').each(function () {
        var $box = $(this),
            $form = $box.parents('form'),
            $input = $box.find('input[type="file"]'),
            $label = $box.find('label'),
            $errorMsg = $box.find('.box__error span'),
            $restart = $box.find('.box__restart'),
            droppedFiles = false,
            autoSubmit = $box.data('autosubmit') || false,
            showFiles = function (files) {
                if (!files) return $label.text('');
                var text = 'Selecione um ficheiro!';
                if (files.length > 1) text = ( $input.attr('data-multiple-caption') || '' ).replace('{count}', files.length);
                if (files.length === 1) text = files[0].name;
                $label.text(text);
            },
            validateFiles = function (files) {
                // ignore the files if they are the same.
                if (files === droppedFiles) return;
                droppedFiles = $input.get(0).files = files;
                $input.data('has-files', true);
                if (files.length > 0) {
                    if (files[0].size > 5 * 1024 * 1024) {
                        var text = 'Tamanho máximo 5mb.';
                        $label.text(text);
                        setTimeout(() => $label.html('<strong>Clique para seleccionar arquivo</strong>' +
                            '<span class="box__dragndrop"><br>ou arraste e solte neste espaço</span>'), 4000);
                        return false;
                    }
                }
                showFiles(files);
                if (autoSubmit) {
                    // automatically submit the form on file drop
                    $form.trigger( 'submit' );
                }
                return multiple ? files.length > 0 : files.length === 1;
            };

        // if (autoSubmit) {
        //     $form.validate({
        //         customProcessStatus: function (status, response) {
        //             // reload page
        //             page(page.current);
        //             return false;
        //         }
        //     });
        // }

        // automatically submit the form on file select
        $input.on('change', function (e) {
            if (e.target.files !== $input.get(0).files || !$input.data('has-files'))
                return validateFiles(e.target.files);
        });

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
                    // the files that were dropped
                    return validateFiles(e.originalEvent.dataTransfer.files);
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
};
module.exports.unload = function () {

};