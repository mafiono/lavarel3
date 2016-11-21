@extends('portal.profile.layout', [
    'active1' => 'banco',
    'middle' => 'portal.bank.head_bank',
    'active2' => 'depositar'])

@section('sub-content')

    <div class="row">
        @include('portal.bank.mini_balance')
        <div class="col-xs-12">
            <div style="margin-top:50px;" class="title">Efectuar depósito (EUR)</div>
            @if ($selfExclusion)
                <div class="brand-descricao descricao-mbottom aleft">
                    O utilizador está auto-excluido.
                </div>
            @else
                @include('portal.bank.deposit_partial')
                <div class="texto" style="margin-top:20px;">
                    Dependendo do método de pagamento utilizado os fornecedores dos serviços de pagamento poderão cobrar taxas por transação conforme a nossa tabela de pagamentos.
                </div>
            @endif
        </div>
    </div>
@stop

@section('scripts')

    {!! HTML::script(URL::asset('/assets/portal/js/jquery.validate.js')); !!}    
    {!! HTML::script(URL::asset('/assets/portal/js/jquery.validate-additional-methods.js')); !!}
    {!! HTML::script(URL::asset('/assets/portal/js/plugins/jquery-form/jquery.form.min.js')); !!}
    {!! HTML::script(URL::asset('/assets/portal/js/forms.js')); !!}
    {!! HTML::script(URL::asset('/assets/portal/js/plugins/rx.umd.min.js')) !!}

    {!! HTML::script(URL::asset('/assets/portal/js/bank/deposit.js')) !!}

@stop

