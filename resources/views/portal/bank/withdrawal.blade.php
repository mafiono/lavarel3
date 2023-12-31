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
        <input type="hidden" name="available" id="available" value="{{ $authUser->balance->balance_available + $authUser->balance->balance_reserved }}">
        <input type="hidden" name="min-amount" id="min-amount" value="{{ in_array($authUser->status->status_id, ['approved', 'pre-approved'], true) ? '10' : '0' }}">
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
                    @foreach ($withdrawAccounts as $bankAccount)
                        @if (!empty($bankAccount->status_id === 'in_use'))
                            <option name="bank_account" value="{{ $bankAccount->id}}"
                                    data-type="{{$bankAccount->transfer_type_id}}"
                                    selected>{{ str_replace('#', '&nbsp;',  str_pad($bankAccount->toName().' ', 23, '#')) . $bankAccount->toHumanFormat() }}</option>
                        @else
                            <option name="bank_account"
                                    data-type="{{$bankAccount->transfer_type_id}}"
                                    value="{{ $bankAccount->id}}">{{ str_replace('#', '&nbsp;',  str_pad($bankAccount->toName().' ', 23, '#')) . $bankAccount->toHumanFormat() }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
        </div>
        @if ($askEmail)
        <div class="form-group row withdraw-email error-placer">
            <div class="col-xs-5">
                {!! Form::label('withdrawal_email', 'Email') !!}
            </div>
            <div class="col-xs-7">
                <div class="input-group">
                    <input id="withdrawal_email" class="form-control" name="withdrawal_email" autocomplete="off">
                </div>
            </div>
            <div class="col-xs-12 place"></div>
        </div>
        @endif
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
        <div class="row" style="padding-top: 15px">
            <div class="col-xs-12">
                <div class="texto">
                    Os pedidos de levantamento serão efetuados na conta acima indicada.
                    A alteração desta conta inviabiliza o processamento de levantamentos por um periodo de 48 horas, necessário para rotinas de confirmação de titular.
                </div>
            </div>
        </div>
        {!! Form::close() !!}
    @endif
@stop