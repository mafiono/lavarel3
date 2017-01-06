@extends('layouts.register')

@section('content')
    <div class="register_step3">
        {!! Form::open(array('route' => 'banco/depositar', 'class' => 'form', 'id' => 'saveForm')) !!}
        <div class="header">
            Está a 1 passo de começar a apostar!
            <i id="info-close" class="fa fa-times"></i>
        </div>
        <div class="content">
            <div align="center" style="margin-top:10px">
                <div class="breadcrumb flat">
                    <a href="#">1. REGISTAR</a>
                    <a href="#">2. VALIDAR</a>
                    <a href="#" class="active">3. DEPOSITAR</a>
                    <a href="#">e</a>
                </div>
            </div>
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
                            <div class="choice">
                                {!! Form::radio('payment_method', 'cc', null, ['id' => 'method_cc']) !!}
                                <label for="method_cc"><img src="/assets/portal/img/thumbs/visa.jpg" alt="" border="0">
                                    Visa
                                </label>
                                <div class="check"><div class="inside"></div></div>
                            </div>
                        </div>
                        <div class="col-xs-4">
                            <div class="choice">
                                {!! Form::radio('payment_method', 'paypal', null, ['id' => 'method_paypal']) !!}
                                <label for="method_paypal">
                                    <img src="/assets/portal/img/thumbs/paypal.jpg" alt="" border="0"> Paypal
                                </label>
                                <div class="check"><div class="inside"></div></div>
                            </div>
                        </div>
                        <div class="col-xs-4">
                            <div class="choice">
                                {!! Form::radio('payment_method', 'meo_wallet', null, ['id' => 'method_meo_wallet']) !!}
                                <label for="method_meo_wallet">
                                    <img src="/assets/portal/img/thumbs/wallet.jpg" alt="" border="0"> Meo Wallet
                                </label>
                                <div class="check"><div class="inside"></div></div>
                            </div>
                        </div>
                    </div>
                    <div class="row icons">
                        <div class="col-xs-4">
                            <div class="choice">
                                {!! Form::radio('payment_method', 'cc', null, ['id' => 'method_mc']) !!}
                                <label for="method_mc">
                                    <img src="/assets/portal/img/thumbs/mastercard.jpg" alt="" border="0"> MasterCard
                                </label>
                                <div class="check"><div class="inside"></div></div>
                            </div>
                        </div>
                        <div class="col-xs-4">
                            <div class="choice">
                                {!! Form::radio('payment_method', 'mb', null, ['id' => 'method_mb']) !!}
                                <label for="method_mb">
                                    <img src="/assets/portal/img/thumbs/mb.jpg" alt="" border="0"> Multibanco
                                </label>
                                <div class="check"><div class="inside"></div></div>
                            </div>
                        </div>
                        <div class="col-xs-4">
                            <div class="choice">
                                {!! Form::radio('payment_method', 'bank_transfer', null, ['id' => 'method_bank_transfer']) !!}
                                <label for="method_bank_transfer">
                                    <img src="/assets/portal/img/thumbs/trans_bank.jpg" alt="" border="0"> Transf. Bancária
                                </label>
                                <div class="check"><div class="inside"></div></div>
                            </div>
                        </div>
                    </div>
                    <div class="row deposit-field" id="deposit_area">
                        <div class="col-xs-8">{{dd('test')}}
                            Introduza o montante que pretende depositar em euros
                        </div>
                        <div class="col-xs-4">
                            <input id="deposit_value" type="text" class="form-control" name="deposit_value" autocomplete="off">
                        </div>
                        <div class="row tax">
                            <div class="col-xs-8 text-right">Taxa</div>
                            <div class="col-xs-4"><input type="text" id="tax" disabled="disabled" value="0.00"></div>
                        </div>
                        <div class="row total">
                            <div class="col-xs-8 text-right">Total</div>
                            <div class="col-xs-4"><input type="text" id="total" disabled="disabled" value="0.00"></div>
                        </div>
                        <div class="texto" style="margin-top:10px;">
                            Dependendo do método de pagamento utilizado os fornecedores dos serviços de pagamento poderão cobrar taxas por transação conforme a nossa
                            <a href="/info/pagamentos">tabela de pagamentos</a>.
                        </div>
                    </div>
                    <div id="deposit_tb" style="display: none;">
                        <div class="row">
                            <div class="col-xs-8">
                                @include('portal.partials.input-text-disabled', [
                                    'field' => 'nome',
                                    'name' => 'Entidade',
                                    'value' => 'Sociedade Figueira Praia, SA',
                                ])
                            </div>
                            <div class="col-xs-4">
                                @include('portal.partials.input-text-disabled', [
                                    'field' => 'nome',
                                    'name' => 'Banco',
                                    'value' => 'Montepio Geral',
                                ])
                            </div>
                            <div class="col-xs-8">
                                @include('portal.partials.input-text-disabled', [
                                    'field' => 'nome',
                                    'name' => 'IBAN',
                                    'value' => 'PT50 0036 0076 9910 0063 5937 3',
                                ])
                            </div>
                            <div class="col-xs-4">
                                @include('portal.partials.input-text-disabled', [
                                    'field' => 'nome',
                                    'name' => 'BIC/SWIFT',
                                    'value' => 'MPIOPTPL',
                                ])
                            </div>
                            <div class="col-xs-12">
                                <div class="texto">
                                    Este depósito poderá demorar até 3 dias úteis. Introduza o seu Id de jogador na Descrição da transferencia.
                                    O Seu ID é  {{$authUser->id}}.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>

                <div class="message">Poderá desde já efectuar o seu primeiro depósito para começar a jogar.
                    <br>Durante este processo será remetido para a página dos nossos parceiros.</div>
            </div>
        </div>
        <div class="footer">
            <div class="actions" style="margin-bottom:10px;">
                <button type="button" class="finish" onclick="top.location.href='/';">CONCLUIR</button>

                <button type="submit" class="deposit">DEPOSITAR</button>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
@stop

@section('scripts')

    <script>
        var taxes = {!! json_encode($taxes) !!};
    </script>
    {!! HTML::script(URL::asset('/assets/portal/js/plugins/autonumeric/autoNumeric-min.js')) !!}
    {!! HTML::script(URL::asset('/assets/portal/js/bank/deposit.js')) !!}

@stop