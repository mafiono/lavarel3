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
            @elseif(!$canDeposit)
                <div class="brand-descricao descricao-mbottom aleft">
                    Esta conta não permite depósitos.
                </div>
            @else
                @include('portal.bank.deposit_partial')
                <div class="row">
                    <div class="col-xs-12">
                        <div class="texto" style="margin-top:20px;">
                            Dependendo do método de pagamento utilizado os fornecedores dos serviços de pagamento poderão cobrar taxas por transação conforme a nossa
                            <a href="/info/pagamentos?back=/perfil/banco/depositar">tabela de pagamentos</a>.
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@stop