var auth = require('../helpers/input-file');
var timeouts = null;
module.exports.load = function(){
    auth.load();
    $('#messages-container').slimScroll({
        //width: '600px',
        height: '330px',
        start: 'bottom',
        allowPageScroll: true
    });

    Handlebars.registerPartial('messages_details', '\
        {{#each .}}\
        <div class="row msg {{#if staff}}staff{{else}}user{{/if}}">\
            <div class="col-xs-12 msg-title">{{created_at}} <span>{{staff}}</span></div>\
            <div class="col-xs-12 msg-body">{{text}}</div>\
        </div>\
        {{/each}}\
        ');

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
    timeouts = window.setInterval(renderMessages, 5000);

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
    if (timeouts) {
        clearInterval(timeouts);
        timeouts = null;
    }
};