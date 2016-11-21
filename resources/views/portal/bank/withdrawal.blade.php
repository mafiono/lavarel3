@extends('portal.profile.layout', [
    'active1' => 'banco',
    'middle' => 'portal.bank.head_bank',
    'active2' => 'levantar'])

@section('sub-content')
    <style>
        .form-registo form span{
            clear: both;
        }
    </style>

    <div class="col-xs-12 fleft">
        <div class="box-form-user-info lin-xs-12">
            <div class="title-form-registo brand-title brand-color aleft">
                Efetuar Levantamento
            </div>

            <div class="brand-descricao descricao-mbottom aleft">                                    
                Todos os pedidos de levantamento, depois de aprovados serão efetuados na sua conta de pagamento abaixo indicada. A alteração da conta de pagamento, impossibilita-o de processar levantamento por um periodo de 48 horas, necessário para rotinas de confirmação de titular.
            </div>

            {!! Form::open(array('route' => 'banco/levantar', 'class' => 'form', 'id' => 'saveForm')) !!} 
                <div class="col-xs-12 fleft">
                    <label for="bank_account">Banco:</label>
                    <select class="acenter" name="bank_account">
                        @foreach ($authUser->confirmedBankAccounts as $bankAccount)
                            @if (!empty($bankAccount->active))
                                <option name="bank_account" value="{{ $bankAccount->id}}" selected>{{ $bankAccount->bank_account .' '. $bankAccount->iban }}</option>
                            @else
                                <option name="bank_account" value="{{ $bankAccount->id}}">{{ $bankAccount->bank_account .' '. $bankAccount->iban }}</option>
                            @endif
                        @endforeach
                    </select>
                    <span></span>
                </div>
                <div class="clear"></div>

            <div class="title-form-registo brand-title mtop brand-color aleft">
                Instruções de Levantamento
            </div>
            <div class="brand-descricao descricao-mbottom aleft">                                    
                Para efetuar um pedido de levantamento a sua conta deve apresentar um saldo mínimo de 100 EUR. Relembramos que poderá realizar até 3 pedidos de levantamento por mês.
            </div>
            <div class="brand-descricao descricao-mbottom aleft">                                    
                <div class="success-color"><b>Saldo Disponível (EUR)</b> {{ $authUser->balance->balance_available }}</div>
                <input type="hidden" name="available" id="available" value="{{ $authUser->balance->balance_available }}">
            </div>

            @if(!$canWithdraw)
                <div class="mini-mbottom">
                <p class="has-error error">A sua conta não permite levantamentos.</p>
                    <div class="clear"></div>
                </div>
            @else
                <div class="mini-mbottom">
                    <label class="col-xs-4 fleft">Valor do levantamento</label>
                    <input class="col-xs-4 acenter fleft" type="text" name="withdrawal_value" id="withdrawal_value" />
                    <span class="has-error error" style="display:none;"> </span>
                    <span></span>
                    <div class="clear"></div>
                </div>
                <div>
                    <label class="col-xs-4 fleft">Sua Password:</label>
                    <input class="col-xs-4 acenter fleft" type="password" autocomplete="off" name="password" id="password" />
                    <span class="has-error error" style="display:none;"> </span>
                    <span></span>
                </div>
                <div>
                    <div class="col-xs-4 form-submit aright fright">
                        <input type="submit" class="col-xs-8 brand-botao brand-link" value="Enviar Pedido" />
                    </div>
                    <div class="clear"></div>
                </div>
            @endif
            {!! Form::close() !!}

        </div>
    </div>
@stop

@section('scripts')

    {!! HTML::script(URL::asset('/assets/portal/js/jquery.validate.js')); !!}    
    {!! HTML::script(URL::asset('/assets/portal/js/jquery.validate-additional-methods.js')); !!}
    {!! HTML::script(URL::asset('/assets/portal/js/plugins/jquery-form/jquery.form.min.js')); !!}
    {!! HTML::script(URL::asset('/assets/portal/js/forms.js')); !!}
    {!! HTML::script(URL::asset('/assets/portal/js/plugins/rx.umd.min.js')) !!}

    {!! HTML::script(URL::asset('/assets/portal/js/bank/withdraw.js')) !!}
@stop

