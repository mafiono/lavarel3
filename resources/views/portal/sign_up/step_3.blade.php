@extends('layouts.register')

@section('styles')
<link media="all" type="text/css" rel="stylesheet" href="/assets/portal/css/register.css">
<link href="https://fonts.googleapis.com/css?family=Exo+2" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
@stop
@section('content')
    <div class="register_step3">
        {!! Form::open(array('route' => 'banco/depositar', 'class' => 'form', 'id' => 'saveForm')) !!}
        <div class="header">
            Está a 1 passo de começar a apostar!
            <i id="info-close" class="fa fa-times"></i>
        </div>
        <div align="center" style="margin-top:10px">
            <div class="breadcrumb flat">
                <a href="#">1. REGISTAR</a>
                <a href="#">2. VALIDAR</a>
                <a href="#" class="active">3. DEPOSITAR</a>
                <a href="#">e</a>
            </div>
        </div>
        <div class="content">
            <div class="icon"><i class="fa fa-check-circle"></i></div>
            <div class="message">A sua conta foi criada com sucesso!<br>
                Foi enviada uma mensagem de confirmação para<br>
                a sua conta de e-mail.</div>
            <hr>
            <div class="title">Faça já o seu primeiro depósito e começe a jogar no Casino Portugal!</div>
            <div class="deposit">
                <div class="bs-wp">
                    <div class="row icons">
                        <div class="col-xs-4">
                            <input type="radio" name="payment_method" id="method_cc" value="cc" checked="checked">
                            <label for="method_cc">
                                <img src="/assets/portal/img/thumbs/visa.jpg" alt="" border="0"> Visa
                            </label>
                        </div>
                        <div class="col-xs-4">
                            <input type="radio" name="payment_method" id="method_paypal" value="paypal">
                            <label for="method_paypal">
                                <img src="/assets/portal/img/thumbs/paypal.jpg" alt="" border="0"> Paypal
                            </label>
                        </div>
                        <div class="col-xs-4">
                            <input type="radio" name="payment_method" id="method_meo_wallet" value="meo_wallet">
                            <label for="method_meo_wallet">
                                <img src="/assets/portal/img/thumbs/wallet.jpg" alt="" border="0"> Meo Wallet
                            </label>
                        </div>
                    </div>
                    <div class="row icons">
                        <div class="col-xs-4">
                            <input type="radio" name="payment_method" id="method_mc" value="cc">
                            <label for="method_mc">
                                <img src="/assets/portal/img/thumbs/mastercard.jpg" alt="" border="0"> MasterCard
                            </label>
                        </div>
                        <div class="col-xs-4">
                            <input type="radio" name="payment_method" id="method_mb" value="mb">
                            <label for="method_mb">
                                <img src="/assets/portal/img/thumbs/mb.jpg" alt="" border="0"> Multibanco
                            </label>
                        </div>
                        <div class="col-xs-4">
                            <input type="radio" name="payment_method" id="method_tb" value="tb">
                            <label for="method_tb">
                                <img src="/assets/portal/img/thumbs/trans_bank.jpg" alt="" border="0"> Transf. Bancária
                            </label>
                        </div>
                    </div>
                    <div class="row field">
                        <div class="col-xs-8">
                            Introduza o montante que pretende depositar em euros
                        </div>
                        <div class="col-xs-4">
                            <input name="deposit" id="deposit" class="required" type="number" step="0.01" min="5.00"
                                   placeholder="5.00">
                        </div>
                    </div>
                </div>
                <hr>

                <div class="message">Poderá concluir este processo e regressar após confirmação da sua conta<br>
                de email ou, se preferir, efetuar desde ja o seu primeiro depósito e<br>
                concluir o registo após voltar a esta página.<br>
                <br>
                O processo de depósito irá remetê-lo por momentos para a<br>
                página dos nossos parceiros.</div>
            </div>
            @include('portal.messages')
        </div>
        <div class="footer">
            <div class="actions" style="margin-bottom:10px;">
                <button type="button" class="finish">CONCLUIR</button>

                <button type="submit" class="deposit">DEPOSITAR</button>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
@stop

@section('scripts')

    {!! HTML::script(URL::asset('/assets/portal/js/jquery.validate.js')); !!}
    {!! HTML::script(URL::asset('/assets/portal/js/jquery.validate-additional-methods.js')); !!}
    {!! HTML::script(URL::asset('/assets/portal/js/plugins/jquery-form/jquery.form.min.js')); !!}
    {!! HTML::script(URL::asset('/assets/portal/js/forms.js')); !!}
    {!! HTML::script(URL::asset('/assets/portal/js/plugins/rx.umd.min.js')) !!}

    {!! HTML::script(URL::asset('/assets/portal/js/registo/step1.js')); !!}
    {!! HTML::script(URL::asset('/assets/portal/js/registo/tooltip.js')); !!}

    <script>

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