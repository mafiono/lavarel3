@extends('layouts.portal')

@section('styles')
    <style>
        .settings-buttons{
            padding: 0;
        }
        .settings-row{
            padding: 7px 0;
        }
        .settings-table {
            clear: both;
            padding-top: 20px;
        }
        .settings-table > thead {
            border: 1px solid #000;
        }
        .settings-table th {
            font-weight: normal;
            font-size: 130%;
            color: #000;
            padding: 5px;
        }

        .settings-table td {
            font-weight: lighter;
            font-size: 110%;
            color: #000;
            padding: 15px 2px;
        }

        .settings-table th:nth-child(1), .settings-table td:nth-child(1){
            width: 120px;
            max-width: 120px;
        }

        .settings-table th:nth-child(2), .settings-table td:nth-child(2) {
            width: 200px;
            max-width: 200px;
        }

        .settings-table th:nth-child(3), .settings-table td:nth-child(3) {
            width: 140px;
            max-width: 140px;

        }

        .settings-table th:nth-child(4), .settings-table td:nth-child(4) {
            width: 80px;
            max-width: 80px;
        }

        .settings-table > tbody {
            overflow: auto;
            max-height: 160px;
        }

        .settings-table > tbody > tr {
            border-bottom: 1px solid #000;
        }

        p.settings-text {
            width: 500px;
        }

        #add-account-form {
            width: 540px;
            padding: 15px 0 0 10px ;
        }

        #add-account-form label {
            display: inline-block;
            width: 90px;
        }

        .settings-table form {
            display: inline;
        }

        .settings-table form button {
            border: 0;
            background: #FFF;
            font-size: 150%;
        }
        .settings-top-margin-normal {
            height: 180px;
        }

        .settings-row .prefix {
            width: 30px;
            display: inline-block;
        }
        .settings-row .settings-textbox {
            width: 300px;
        }
        .settings-row .with-prefix {
            width: 264px;
        }
    </style>
@stop
<?php
        echo "hello"
?>
@section('content')
    @include('portal.profile.head', ['active' => 'BANCO'])

    @include('portal.bank.head_bank', ['active' => 'CONTA DE PAGAMENTOS'])

    <div class="settings-col">

        <table class="settings-table">
            <thead>
            <tr>
                <th>Banco</th>
                <th>IBAN</th>
                <th>Estado</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @if ($authUser->bankAccounts->count()>0)
                @foreach($authUser->bankAccounts as $account)
                <tr>
                    <td>{{$account->bank_account}}</td>
                    <td>{{$account->iban}}</td>
                    <td>{{$account->status->name}}</td>
                    <td>
                        {!! Form::open(['url' => 'banco/conta-pagamentos/'.$account->id.'/remover', 'method' => 'delete']) !!}
                            <button class="fa fa-times-circle warning-color remove-account" alt="Apagar" title="Apagar?"></button>
                        {!! Form::close() !!}
                    </td>
                </tr>
                @endforeach
            @else
                <tr>
                    <td></td>
                    <td>Não tem contas bancarias associadas.</td>
                    <td></td>
                    <td></td>
                </tr>
            @endif
            </tbody>
        </table>
            @if ($authUser->confirmedBankAccounts->count()>0)
            <div class="settings-row settings-buttons settings-top-margin-small aright">
                <button id="add-account-btn" class="settings-button settings-button-selected">Adicionar Conta</button>
                <button id="select-account-btn" class="settings-button">Selecionar Conta</button>
            </div>
            @endif

            <div id="add-account-container" class="settings-top-margin-normal">
                <h2 class="settings-title">Adicionar Conta de Pagamentos</h2>
                {!! Form::open(['url' => 'banco/conta-pagamentos', 'method' => 'put', 'id' => 'add-account-form']) !!}
                <div class="settings-row">
                    <label for="banco">Banco</label>
                    <input class="settings-textbox" type="text" id="bank" name="bank" placeholder="Banco" required>
                </div>
                <div class="settings-row">
                    <label for="iban">IBAN</label>
                    <span class="prefix">PT50</span><input class="settings-textbox with-prefix" type="text" id="iban" name="iban" placeholder="IBAN" required>
                    <span class="has-error error"></span>
                </div>
                <div class="settings-row" >
                    <label for="upload">Comprovativo</label>
                    <input type="file" id="upload" name="upload" class="required col-xs-6 brand-botao brand-link settings-textbox" accept="application/pdf" />
                    <span class="has-error error"></span>
                </div>
                <div class="settings-row">
                    <button class="settings-submit-button fright">Adicionar</button>
                </div>
                {!! Form::close() !!}
            </div>

            <div id="select-account-container" class="settings-top-margin-normal settings-hidden">
                <h2 class="settings-title">Selecionar Conta de Pagamentos</h2>
                <div class="settings-row">
                    <p class="settings-text">
                        Todos os pedidos de levantamento, depois de aprovados serão efectuados na sua conta de pagamento abaixo indicada.
                        A alteração da conta de pagamento, impossibilita-o de processar levandamento por um periodo de 48 horas, necessário
                        para routinas de confirmação de titular.
                    </p>
                </div>
                {!! Form::open(['url' => 'banco/conta-pagamentos', 'method' => 'post']) !!}
                <div class="settings-row">
                    <select id="selected-account" name="selected_account">
                        @foreach($authUser->confirmedBankAccounts as $account)
                            <option value="{{$account->id}}">{{$account->bank_account}} - {{$account->iban}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="settings-row">
                    <button class="settings-submit-button fright">Alterar</button>
                </div>
                {!! Form::close() !!}
            </div>
    </div>

    @include('portal.profile.bottom')

@stop

@section('scripts')
    {!! HTML::script(URL::asset('/assets/portal/js/jquery.validate.js')) !!}
    {!! HTML::script(URL::asset('/assets/portal/js/jquery.validate-additional-methods.js')) !!}
    {!! HTML::script(URL::asset('/assets/portal/js/plugins/jquery-form/jquery.form.min.js')) !!}
    {!! HTML::script(URL::asset('rx.umd.min.js')) !!}

    {!! HTML::script(URL::asset('/assets/portal/js/bank/accounts.js')) !!}

@stop

