@extends('portal.profile.layout', [
    'active1' => 'banco',
    'middle' => 'portal.bank.head_bank',
    'active2' => 'depositar'])

@section('sub-content')

    <div class="title">
        Saldo (EUR)
    </div>

    <div class="profile-table">
        <table>
            <thead>
            <tr>
                <th style="text-align: left">Disponível</th>
                <th style="text-align: left">Contabilistico</th>
                <th style="text-align: right">Bónus</th>
                <th style="text-align: right">Total</th>
            </tr>
            </thead>

            <tbody>
            <tr>
                <td >{{ $authUser->balance->balance_available }}</td>
                <td >{{ $authUser->balance->balance_accounting }}</td>
                <td style="text-align: right" >{{ $authUser->balance->balance_bonus }}</td>
                <td style="text-align: right"><b>{{ $authUser->balance->balance_total }}</b></td>
            </tr>
            </tbody>
        </table>

    </div>

    @if ($selfExclusion)
            <div class="brand-descricao descricao-mbottom aleft">
                O utilizador está auto-excluido.
            </div>
        @else

        <div style="margin-top:50px;" class="title">Efectuar depósito (EUR)</div>
            @include('portal.bank.deposit_partial')


        @endif

    <div class="clear"></div>
    <div class="texto" style="margin-top:20px;">
        Dependendo do método de pagamento utilizado os fornecedores dos serviços de pagamento poderão cobrar taxas por transação conforme a nossa tabela de pagamentos.
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

