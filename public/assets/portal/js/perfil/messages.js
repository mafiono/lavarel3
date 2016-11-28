$(function () {

    Handlebars.registerPartial('messages_details', '\
        {{#each .}}\
        <div class="row msg {{#if staff}}staff{{else}}user{{/if}}">\
            <div class="col-xs-12 msg-title">{{created_at}} <span>{{staff}}</span></div>\
            <div class="col-xs-12 msg-body">{{text}}</div>\
        </div>\
        {{/each}}\
        ');

    $('#file_iban').click(function () {
        $('#fileChooser').trigger('click');
    });
    $("#newmessage").submit(function () {
        var message = $("#messagebody").val();
        var image = $("#fileChooser").val();
        $("#messagebody").val('');
        $("#fileChooser").val('');

        $.post("/perfil/mensagens/new", {'message': message, 'image': image})
            .done(renderMessages());
        return false;
    });

    function ValidateFileUpload() {

        var fuData = document.getElementById('fileChooser');
        var FileUploadPath = fuData.value;


        if (FileUploadPath == '') {
            swal("Erro", "Por favor selecione uma imagem.", 'error');

        } else {
            var Extension = FileUploadPath.substring(FileUploadPath.lastIndexOf('.') + 1).toLowerCase();


            if (Extension == "gif" || Extension == "png" || Extension == "bmp"
                || Extension == "jpeg" || Extension == "jpg") {


                if (fuData.files && fuData.files[0]) {

                    var size = fuData.files[0].size;

                    if (size > 50000) {
                        swal("Limite máximo: 50Kbs", 'error');
                        fuData.value = "";
                        return;
                    }
                    if (confirm("Tem a certeza?")) {
                        $("#newmessage").submit();
                    } else {
                        $("#fileChooser").val('');
                    }
                }
            }
            else {
                swal({
                    title: "Tipo de Imagem inválida",
                    text: "Apenas são aceites os seguintes tipos de imagem:<br> GIF, PNG, JPG, JPEG and BMP.",
                    type: 'error',
                    html: true
                });
                fuData.value = "";
            }

        }
    }

    $(renderMessages);

    // window.setInterval(renderMessages, 5000);

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
    });
});