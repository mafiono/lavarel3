var auth = require('../helpers/input-file');
var timeouts = false;
module.exports.load = function(){
    auth.load();
    timeouts = true;
    $('#messages-container').slimScroll({
        //width: '600px',
        height: '330px',
        start: 'bottom',
        allowPageScroll: true
    });

    var scrollOnNext = true;
    var items = 0;

    var $newmessage = $("#newmessage");
    $newmessage.validate({
        customProcessStatus: function (status, response) {
            scrollOnNext = true;
            $newmessage.find('#image').val('');
            $newmessage.find('#messagebody').val('');
            $newmessage.find('.box label')
                .html('<strong>Clique para seleccionar arquivo</strong><span class="box__dragndrop"><br>ou arraste e solte neste espaço</span>');

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