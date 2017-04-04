@extends('portal.profile.layout', [
    'active1' => 'banco',
    'middle' => 'portal.bank.head_bank',
    'active2' => 'levantar'])
@section('header')
    <link href="https://fonts.googleapis.com/css?family=Droid+Sans+Mono" rel="stylesheet">
@stop
@section('sub-content')

    <div class="row">
        @include('portal.bank.mini_balance')
        <div class="col-xs-12">
            <div class="title">
                Efetuar Levantamento
            </div>
        </div>
    </div>

    @if(!$canWithdraw)
        <div class="row">
            <div class="col-xs-12">
                <div class="texto">
                    A sua conta não permite levantamentos. Para efetuar levantamentos terá de ter verificado os seguintes pontos.
                </div>
            </div>
            @include('portal.bank.validation-partial', [
                'name' => 'Validação de identidade',
                'status' => $whyWithdraw['identity_status_id'],
                'url' => '/perfil/autenticacao'
            ])
            @include('portal.bank.validation-partial', [
                'name' => 'Validação de Morada',
                'status' => $whyWithdraw['address_status_id'],
                'url' => '/perfil/autenticacao'
            ])
            @include('portal.bank.validation-partial', [
                'name' => 'Conta de Pagamentos',
                'status' => $whyWithdraw['iban_status_id'],
                'url' => '/perfil/banco/conta-pagamentos'
            ])
            @include('portal.bank.validation-partial', [
                'name' => 'Validação de e-mail',
                'status' => $whyWithdraw['email_status_id'],
            ])
        </div>
    @else
        {!! Form::open(array('route' => 'banco/levantar', 'class' => 'form', 'id' => 'saveForm')) !!}
        <input type="hidden" name="available" id="available" value="{{ $authUser->balance->balance_available }}">
        <div class="row withdraw-bank">
            <div class="col-xs-4">
                <label for="bank_account">Nome</label>
            </div>
            <div class="col-xs-7 iban">
                <label for="bank_account">Identificador</label>
            </div>
        </div>
        <div class="row withdraw-bank">
            <div class="col-xs-12">
                <select id="bank_account" name="bank_account">
                    @foreach ($authUser->confirmedBankAccounts as $bankAccount)
                        @if (!empty($bankAccount->active))
                            <option name="bank_account" value="{{ $bankAccount->id}}"
                                    selected>{{ str_replace('#', '&nbsp;',  str_pad($bankAccount->toName().' ', 24, '#')) . $bankAccount->toHumanFormat() }}</option>
                        @else
                            <option name="bank_account"
                                    value="{{ $bankAccount->id}}">{{ str_replace('#', '&nbsp;',  str_pad($bankAccount->toName().' ', 24, '#')) . $bankAccount->toHumanFormat() }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group row withdraw-amount error-placer">
            <div class="col-xs-5">
                {!! Form::label('withdrawal_value', 'Introduza montante') !!}
            </div>
            <div class="col-xs-4">
                <div class="input-group">
                    <input id="withdrawal_value" class="form-control" name="withdrawal_value" autocomplete="off">
                </div>
            </div>
            <div class="col-xs-3">
                <input type="submit" value="Levantar"/>
            </div>
            <div class="col-xs-12 place"></div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <div class="texto">
                    Os pedidos de levantamento serão efetuados na conta acima indicada.
                    A altiração desta conta inviabiliza o processamento de levantamentos por um periodo de 48 horas, necessário para rotinas de confirmação de titular.
                </div>
            </div>
        </div>
        {!! Form::close() !!}
    @endif
@stop