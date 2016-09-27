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
    <div class="register_step3">
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
            <div class="header">A sua conta foi criada com sucesso! Foi enviada uma mensagem de confirmação para o seu e-mail.</div>
        </div>
        <div class="footer">
            <div class="header">Faça já o seu primeiro depósito e começe a jogar na Bet Portugal!</div>
            <div class="deposit">

                @include('portal.bank.deposit_partial')

            </div>


            </div>
        </div>





    <script>

        $(document).ready(function(){
            $('input[type="radio"]').click(function(){
                if($(this).attr("value")=="red"){
                    $(".box").not(".red").hide();
                    $(".red").show();
                }
                if($(this).attr("value")=="green"){
                    $(".box").not(".green").hide();
                    $(".green").show();
                }
                if($(this).attr("value")=="blue"){
                    $(".box").not(".blue").hide();
                    $(".blue").show();
                }
            });
        });
        $("#imagem").click(function () {
            $("#upload").trigger('click');
        });
        $("#concluir").click(function () {
            $.post( "/registar/step3", function( data ) {
                if(data['status4'] == "success")
                {
                    top.location.replace("/concluiregisto/");
                }
            })});


        $('#info-close').click(function(){

            top.location.replace("/");
        });
        $('#limpar').click(function(){
            $('#saveForm')[0].reset();
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