var auth = require('../../helpers/input-file');
var timeouts = false;
module.exports.load = function(){
    auth.load();
    timeouts = true;
    $('#messages-container').slimScroll({
        //width: '600px',
        height: '330px',
        start: 'bottom',
        allowPageScroll: false
    });

    var scrollOnNext = true;
    var items = 0;

    var $newmessage = $("#newmessage");
    $newmessage.validate({
        beforeSubmit: false,
        customProcessStatus: function (status, response) {
            scrollOnNext = true;
            $newmessage.find('#messagebody').val('');

            $newmessage.find('#messagebody').focus();
            // use the default logic
            return false;
        }
    });

    var $newupload = $("#newupload");
    $newupload.validate({
        beforeSubmit: function beforeSubmit(form) {
            $.fn.popup({
                title: 'Aguarde por favor!',
                type: 'warning',
                text: '<div class="bs-wp"><div class="progress">\
                    <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0%">\
                        <span class="sr-only">0% Completo</span>\
                    </div>\
                </div></div>',
                html: true,
                showCancelButton: false,
                showConfirmButton: false
            });
        },
        customProcessStatus: function (status, response) {
            scrollOnNext = true;
            $newupload.find('#image').val('');
            $newupload.find('.box label')
                .html('<strong>Clique para seleccionar arquivo</strong><span class="box__dragndrop"><br>ou arraste e solte neste espaço</span>');

            $newupload.find('#messagebody').focus();
            // use the default logic
            return false;
        }
    });

    setTimeout(renderMessages, 5000);

    function renderMessages() {
        $.get('/ajax-perfil/perfil/mensagens/chat').done(function (data) {
            if (typeof data === 'string') {
                data = JSON.parse(data);
            }
            if (items !== data.length){
                items = data.length;
                scrollOnNext = true;
                $('#messages-count').text(items);
            }
            var html = Template.apply('messages_details', data);

            if (scrollOnNext) {
                scrollOnNext = false;
                $("#messages-container").html(html);
                $('#messages-container').slimScroll({ scrollTo: '9999' });
            }
        });
        if (timeouts)
            setTimeout(renderMessages, 5000);
    }

    setTimeout(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
        $.ajax({
            type: "POST",
            url: '/perfil/mensagens/read',
            dataType: "json"
        });
    }, 3000);
};
module.exports.unload = function () {
    auth.unload();
    timeouts = false;
};