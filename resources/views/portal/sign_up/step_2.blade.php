@extends('layouts.register')

<link media="all" type="text/css" rel="stylesheet" href="/assets/portal/css/register.css">
<link href="https://fonts.googleapis.com/css?family=Exo+2" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">

<?php
    if(isset($selfexclusion))
{
    header('/');
}
?>
@section('content')
    <div class="register_step2">
        <div class="header">
            Está a 1 passo de começar a apostar!
            <i id="info-close" class="fa fa-times"></i>
        </div>
        <div class="content">
            <div align="center" style="margin-top:10px">
                <div class="breadcrumb flat">
                    <a href="#" >1. REGISTO</a>
                    <a href="#" class="active">2. VALIDAÇÃO</a>
                    <a href="#">e</a>
                </div>
            </div>
            <div class ="icon"><i class="fa fa-check-circle"></i></div>
            <div class="header">A sua conta foi criada com sucesso!</div>

            <div class ="icon"><i class="fa fa-exclamation-circle"></i></div>
                <div class="header">Infelizmente, não nos foi possível verificar a sua identidade com base nos dados introduzidos! Por favor forneça um documento comprovativo.</div>
        </div>
        <div class="footer">
            <div class="upload"> <div id="imagem" style="cursor:pointer;"> <img src="/assets/portal/img/uploadregisto.png" /></div>
                {!! Form::open(array('url'=>'/registar/step2','method'=>'POST', 'files'=>true)) !!}

                    <div style="display:none"><input type="File" name="upload" id="upload">
                    </div>
                    <div id="ficheiro"></div>

            <div class="actions" style="margin-bottom:10px;">
                <button type="submit" class="submit">CONCLUIR</button>


            </div>
                {!! Form::close() !!}
            </div>
        </div>



        </div>

    <script>

        $("#imagem").click(function () {
            $("#upload").trigger('click');
        });
        $('#upload').change(function(){
            var fileName = $(this).val();
            $('#ficheiro').text(fileName);
        });

        $('#info-close').click(function(){

            top.location.replace("/");
        });
        $('#limpar').click(function(){
            document.location.href="/registar/step1";
        });
    </script>
@stop

@section('scripts')

    {!! HTML::script(URL::asset('/assets/portal/js/jquery.validate.js')); !!}
    {!! HTML::script(URL::asset('/assets/portal/js/jquery.validate-additional-methods.js')); !!}
    {!! HTML::script(URL::asset('/assets/portal/js/plugins/jquery-form/jquery.form.min.js')); !!}
    {!! HTML::script(URL::asset('/assets/portal/js/forms.js')); !!}
    {!! HTML::script(URL::asset('/assets/portal/js/plugins/rx.umd.min.js')) !!}

    {!! HTML::script(URL::asset('/assets/portal/js/registo/step1.js')); !!}
    {!! HTML::script(URL::asset('/assets/portal/js/registo/tooltip.js')); !!}

@stop