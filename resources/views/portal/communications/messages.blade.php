@extends('portal.profile.layout', [
    'active1' => 'perfil',
    'middle' => 'portal.profile.head_profile',
    'active2' => 'autenticacao'])
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
<div id="messages-container">

</div>


@stop





@section('scripts')
    <script>

        document.getElementById("messagebox").scrollTop = document.getElementById("messagebox").scrollHeight;
        setTimeout(function(){
            window.location.reload(1);
        }, 20000);


    </script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>

    <script src="/assets/portal/js/bootstrap-filestyle.min.js"> </script>
    <script>

        $(renderMessages);

        window.setInterval(renderMessages, 10000);

        function renderMessages() {
            $.get('/chat').done(function (data) {
                $("#messages-container").html(data);

                document.getElementById("messagebox").scrollTop = document.getElementById("messagebox").scrollHeight;
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