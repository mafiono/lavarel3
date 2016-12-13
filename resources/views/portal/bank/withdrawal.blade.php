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

    @if(count($canWithdraw) > 0)
        <div class="row">
            <div class="col-xs-12">
                <div class="texto">
                    A sua conta não permite levantamentos.
                    @foreach($canWithdraw as $key => $value)
                        <br>{{$key}} => {{$value}}
                    @endforeach
                </div>
            </div>
        </div>
    @else
        {!! Form::open(array('route' => 'banco/levantar', 'class' => 'form', 'id' => 'saveForm')) !!}
        <input type="hidden" name="available" id="available" value="{{ $authUser->balance->balance_available }}">
        <div class="row withdraw-bank">
            <div class="col-xs-4">
                <label for="bank_account">Banco</label>
            </div>
            <div class="col-xs-7 iban">
                <label for="bank_account">IBAN</label>
            </div>
        </div>
        <div class="row withdraw-bank">
            <div class="col-xs-12">
                <select id="bank_account" name="bank_account">
                    @foreach ($authUser->confirmedBankAccounts as $bankAccount)
                        @if (!empty($bankAccount->active))
                            <option name="bank_account" value="{{ $bankAccount->id}}"
                                    selected>{{ str_replace('#', '&nbsp;',  str_pad($bankAccount->bank_account.' ', 24, '#')) . $bankAccount->iban }}</option>
                        @else
                            <option name="bank_account"
                                    value="{{ $bankAccount->id}}">{{ str_replace('#', '&nbsp;',  str_pad($bankAccount->bank_account.' ', 24, '#')) . $bankAccount->iban }}</option>
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
                    <input id="withdrawal_value" type="number" min="10" class="form-control" name="withdrawal_value">
                </div>
            </div>
            <div class="col-xs-3">
                <input type="submit" value="Levantar"/>
            </div>
            <div class="col-xs-12">
                <span class="has-error error place" style="display:none;"> </span>
            </div>
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

@section('scripts')
    {!! HTML::script(URL::asset('/assets/portal/js/plugins/rx.umd.min.js')) !!}
    {!! HTML::script(URL::asset('/assets/portal/js/bank/withdraw.js')) !!}
@stop

