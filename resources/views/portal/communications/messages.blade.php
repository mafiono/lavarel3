@extends('portal.profile.layout', [
    'active1' => 'comunicacao',
    'middle' => 'portal.communications.head_communication',
    'active2' => 'mensagens'])
<!-- Bootstrap 3.3.5 -->
<link rel="stylesheet" href="/assets/portal/bootstrap/bootstrap.min.css">
<!-- Font Awesome -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
<!-- Ionicons -->
<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
<!-- Theme style -->
<!--<link rel="stylesheet" href="/assets/css/AdminLTE.min.css">-->
<link rel="stylesheet" href="/assets/portal/css/AdminLTE.css">
<link rel="stylesheet" href="/assets/portal/css/skins/skin-blue.min.css">
<link rel="stylesheet" href="/assets/portal/css/custom.css">


@section('sub-content')
<div >
    <div class="box box-success direct-chat direct-chat-success">
        <div class="box-header with-border">
            <h3 class="box-title">Staff Messages</h3>


        </div>
        <!-- /.box-header -->
        <div id="messages-container"   class="box-body">
        </div>
    </div>
</div>
    <div class="box-footer" >
        <form id="newmessage" action="/sendmessage" method="post" enctype="multipart/form-data">
            {!! Form::token() !!}
            <input type="hidden" name="id" value={{Auth::user()->id}}>
            <div class="input-group" style="width: 300px">
                <input type="text" id="messagebody" name="message" placeholder="Type Message ..." class="form-control" style="height: 34px">
                    <span class="input-group-btn">
                        <button id="cenas" class="btn btn-success btn-flat">Send</button>
                    </span>
            </div>
            <div class="input-group">
                <input  type="file" id="fileChooser" class="filestyle" name="image" data-classButton="btn btn-primary" data-input="false" data-classIcon="icon-plus" data-buttonText="Upload Image" style="height: 34px; margin-top: 10px; padding: 5px 20px;" onchange="return ValidateFileUpload()">
            </div>
        </form>
    </div>




@stop





@section('scripts')


<script>

//    $("form[name='newmessage']").submit(function(e) {
//        $("#messagebody").val('');
//        $("#fileChooser").cancelSelection();
//
//        var formData = new FormData($(this)[0]);
//
//        $.ajax({
//            url: "/sendmessage",
//            type: "POST",
//            data: formData,
//            async: false,
//            success: function (msg) {
//                renderMessages2();
//            },
//            cache: false,
//            contentType: false,
//            processData: false
//        });
//
//        e.preventDefault();
//    });
    $("#cenas").click(function(){
        var message = $("#messagebody").val();
        var image = $("#fileChooser").val();
        $("#messagebody").val('');
        $("#fileChooser").val('');

        $.post("/sendmessage", {'message' : message , 'image' : image})
                .done(renderMessages2());
        return false;
    });

</script>



    <script>
        function ValidateFileUpload() {

            var fuData = document.getElementById('fileChooser');
            var FileUploadPath = fuData.value;


            if (FileUploadPath == '') {
                alert("Please upload an image");

            } else {
                var Extension = FileUploadPath.substring(FileUploadPath.lastIndexOf('.') + 1).toLowerCase();



                if (Extension == "gif" || Extension == "png" || Extension == "bmp"
                        || Extension == "jpeg" || Extension == "jpg") {


                    if (fuData.files && fuData.files[0]) {

                        var size = fuData.files[0].size;

                        if(size > 50000){
                            alert(size);
                            alert("Limite máximo: 50Kbs");
                            fuData.value = "";
                            return;
                        }
                       if( confirm("Tem a certeza?") ) {
                           $("#newmessage").submit();
                       }else{
                           $("#fileChooser").val('');
                       }
                }

                }


                else {
                    alert("Photo only allows file types of GIF, PNG, JPG, JPEG and BMP. ");
                    fuData.value = "";
                }

            }}
    </script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>

    <script src="/assets/portal/js/bootstrap-filestyle.min.js"> </script>
    <script>

        $(renderMessages2);



        function renderMessages2() {

            $.get('/chat').done(function (data) {
                $("#messages-container").html(data);
                document.getElementById("messagebox").scrollTop = document.getElementById("messagebox").scrollHeight;
            });
        }
        window.setInterval(renderMessages, 10000);
        function renderMessages() {
            var position = $("#messagebox").scrollTop();

            $.get('/chat').done(function (data) {
                $("#messages-container").html(data);

                $("#messagebox").scrollTop(position);
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
    url: '/mensagens/read',
    dataType: "json",

    });

    });

    </script>
@stop