$(function () {

    Handlebars.registerPartial('messages_details', '\
        {{#each .}}\
        <div class="row msg {{#if staff}}staff{{else}}user{{/if}}">\
            <div class="col-xs-12 msg-title">{{created_at}} <span>{{staff}}</span></div>\
            <div class="col-xs-12 msg-body">{{text}}</div>\
        </div>\
        {{/each}}\
        ');

    $("#newmessage").validate();

    window.setInterval(renderMessages, 5000);

    function renderMessages() {
        $.get('/perfil/mensagens/chat').done(function (data) {
            if (typeof data === 'string') {
                data = JSON.parse(data);
            }
            var scrollHeight = document.getElementById("messages-container").scrollHeight;
            var scrollTop = document.getElementById("messages-container").scrollTop;
            var relativePosition = scrollTop / scrollHeight;
            console.log(data);
            var html = Template.apply('messages_details', data);

            $("#messages-container").html(html);

            var newScrollHeight = document.getElementById("messages-container").scrollHeight;
            $("#messages-container").scrollTop(relativePosition * newScrollHeight);
        });

    }

    $(document).ready(function () {
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
    });
});