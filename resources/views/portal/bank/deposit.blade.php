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
            @elseif($reserved > 0)
                <div class="brand-descricao descricao-mbottom aleft">
                    Esta conta têm {{number_format($reserved, 2, ',', ' ')}} € em Cativo.

                    @if ($maxLimitFromReserve > 0)
                        <br>Atualmente pode passar para saldo disponível {{number_format($maxLimitFromReserve, 2, ',', ' ')}} €.

                        <div class="form-group row reserve-amount error-placer">
                        {!! Form::open(array('route' => 'banco/transferir', 'class' => 'form', 'id' => 'saveForm')) !!}
                            <div class="col-xs-5">
                                {!! Form::label('reserve_value', 'Introduza montante') !!}
                            </div>
                            <div class="col-xs-4">
                                <div class="input-group">
                                    <input id="reserve_value" class="form-control"
                                           data-max="{{$maxLimitFromReserve}}"
                                           name="reserve_value" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-xs-3">
                                <input type="submit" value="Transferir"/>
                            </div>
                            <div class="col-xs-12 place"></div>
                        {!! Form::close() !!}
                        </div>
                    @endif
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