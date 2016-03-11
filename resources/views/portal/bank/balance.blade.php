@extends('layouts.portal', ['mini' => true])

@section('content')

    @include('portal.profile.head', ['active' => 'BANCO'])

    @include('portal.bank.head_bank', ['active' => 'SALDO'])

    <div class="col-xs-7 lin-xs-10 fleft">
        <div class="box-form-user-info lin-xs-12">
            <div class="title-form-registo brand-title brand-color aleft">
                Saldo
            </div>

            @include('portal.messages')
            
            <div class="brand-descricao mini-mbottom aleft">                                    
                <b class="neut-color">O Seu Saldo (EUR)</b>
            </div>
            
            <div class="table_user mini-mbottom">
                <table class="col-xs-12 neut-color">
                    <thead>
                        <tr>
                            <th class="col-xs-3 brand-color aleft">Disponível</th>
                            <th class="col-xs-3 brand-color aleft">Contabilistico</th>
                            <th class="col-xs-3 brand-color aleft">Bónus</th>
                            <th class="col-xs-3 brand-color aleft">Total</th>
                        </tr>
                    </thead>
                    
                    <tbody>
                        <tr>
                            <td class="col-xs-3 aleft">{{ $authUser->balance->balance_available }}</td>
                            <td class="col-xs-3 aleft">{{ $authUser->balance->balance_accounting }}</td>
                            <td class="col-xs-3 aleft">{{ $authUser->balance->balance_bonus }}</td>
                            <td class="col-xs-3 aleft"><b>{{ $authUser->balance->balance_total }}</b></td>
                        </tr>
                    </tbody>
                </table>
                <div class="col-xs-4 fleft">
                    <a href="/banco/depositar">
                        <div class="col-xs-12 brand-botao brand-botao-type brand-link">Efetuar Depósito</div>
                    </a>
                </div>
                <div class="col-xs-4 fleft" style="margin-left:30px;">
                    <a href="/banco/levantar">
                        <div class="col-xs-12 brand-botao brand-botao-type brand-link">Efetuar Levantamento</div>
                    </a>
                </div>
                <div class="clear"></div>
            </div>

            <div class="brand-descricao mini-mbottom aleft">                                    
                <b class="neut-color">Bónus Activos (EUR)</b>
            </div>
            
            <div class="table_user neut-color mini-mbottom">
                <table class="col-xs-8">
                    <thead>
                        <tr>
                            <th class="col-xs-6 brand-color aleft">Conta</th>
                            <th class="col-xs-6 brand-color aleft">Bónus</th>
                        </tr>
                    </thead>
                    
                    <tbody>
                        <tr>
                            <td class="col-xs-6 gray-border bborder aleft">Desportos</td>
                            <td class="col-xs-6 gray-border bborder aleft"><b>0,00</b></td>
                        </tr>
                        <tr>
                            <td class="col-xs-6 gray-border bborder aleft">Casino</td>
                            <td class="col-xs-6 gray-border bborder aleft"><b>0,00</b></td>
                        </tr>
                        <tr>
                            <td class="col-xs-6 gray-border bborder aleft">Póker</td>
                            <td class="col-xs-6 gray-border bborder aleft"><b>0,00</b></td>
                        </tr>
                        <tr>
                            <td class="col-xs-6 aleft">Jogos/Vegas</td>
                            <td class="col-xs-6 aleft"><b>0,00</b></td>
                        </tr>
                    </tbody>
                </table>
                <div class="col-xs-12">
                    <a href="/banco/consultar-bonus">
                        <div class="col-xs-4 brand-botao brand-botao-type fright brand-link">Consultar Bónus</div>
                    	<div class="clear"></div>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="clear"></div>

    @include('portal.profile.bottom')    

@stop

@section('scripts')

{!! HTML::script(URL::asset('/assets/portal/js/plugins/jquery-form/jquery.form.min.js')); !!}
{!! HTML::script(URL::asset('/assets/portal/js/forms.js')); !!}

@stop

