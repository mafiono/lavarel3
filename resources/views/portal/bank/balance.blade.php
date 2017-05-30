@extends('portal.profile.layout', [
    'active1' => 'banco',
    'middle' => 'portal.bank.head_bank',
    'active2' => 'saldo'])

@section('sub-content')

    <div class="row">
        @include('portal.bank.mini_balance')
        <div class="col-xs-12">

            <div class="left">
                <div class="title">Carteira de Bónus (EUR)</div>
            </div>

            <div class="profile-2table">
                <table>
                    <thead>
                    <tr>
                        <th style="text-align:left">Tipo</th>
                        <th style="text-align:right">Montante</th>
                    </tr>
                    </thead>

                    <tbody>
                    <tr>
                        <td>Desportos</td>
                        <td style="text-align:right"><b>0.00</b></td>
                    </tr>
                    <tr>
                        <td>Casino</td>
                        <td style="text-align:right"><b>0.00</b></td>
                    </tr>
                    </tbody>
                </table>

            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <div class="title">Bónus disponíveis</div>
        </div>
    </div>

    @if(count($availableSportBonuses) > 0)
        <div class="bonus table-like">
            <div class="row header">
                <div class="col-xs-7">Desportos</div>
                <div class="col-xs-2 text-center">Bónus</div>
                <div class="col-xs-3"></div>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <div class="place">
                        @foreach($availableSportBonuses as $bonus)
                            <div class="row">
                                <div class="col-xs-7">{{$bonus->title}}</div>
                                <div class="col-xs-2 text-center"><i class="cp-exclamation-circle cp-2x"></i></div>
                                <div class="col-xs-3 text-center button"><a href="/bonus/redeem/{{$bonus->id}}" class="btn btn-success redeem"
                                                                            data-id="{{$bonus->id}}" data-title="{{$bonus->title}}">Utilizar</a></div>
                                <div class="bag">
                                    <div class="details">
                                        <div class="row">
                                            <div class="col-xs-6">Depósito mínimo: <b>€ {{number_format($bonus->min_deposit, 0, ' ', ' ')}}</b></div>
                                            <div class="col-xs-6">Depósito máximo: <b>€ {{number_format($bonus->max_deposit, 0, ' ', ' ')}}</b></div>

                                            <div class="col-xs-6">Cota mínima: <b>{{$bonus->min_odd}}</b></div>
                                            <div class="col-xs-6">Válido durante: <b>{{$bonus->deadline}} dias</b></div>

                                            <div class="col-xs-12">Montante apostado: <b>{{number_format($bonus->rollover_coefficient, 0, ' ', ' ')}} x (valor depósito + valor bónus)</b></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="row">
            <div class="col-xs-12">
                <div class="texto">Não existem bónus disponíveis.</div>
            </div>
        </div>
    @endif

    <div class="row">
        <div class="col-xs-12">
            <div class="title">Bónus ativos</div>
        </div>
    </div>

    @if(count($activeSportBonuses) > 0)
        <div class="bonus table-like">
            <div class="row header">
                <div class="col-xs-4">Desportos</div>
                <div class="col-xs-2 text-center">Bónus</div>
                <div class="col-xs-2 text-center">Apostas</div>
                <div class="col-xs-3 text-center">Objectivo</div>
                <div class="col-xs-1"></div>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <div class="place">
                        @foreach($activeSportBonuses as $bonus)
                            <div class="row">
                                <div class="col-xs-3">{{$bonus->bonus->title}}</div>
                                <div class="col-xs-1 text-center"><i class="cp-exclamation-circle cp-2x"></i></div>
                                <div class="col-xs-2 text-center">€ {{number_format($bonus->bonus_value, 0, ' ', ' ')}}</div>
                                <div class="col-xs-2 text-center">€ {{$bonus->balance_bonus}}</div>
                                <div class="col-xs-3 text-center">€ {{number_format($bonus->rollover_amount, 0, ' ', ' ')}}</div>
                                <div class="col-xs-1 text-center button">
                                    <a href="/bonus/cancel/{{$bonus->id}}" class="cp-2x cp-times cancel"
                                       data-id="{{$bonus->id}}" data-title="{{$bonus->bonus->title}}"></a>
                                </div>
                                <div class="bag">
                                    <div class="details">
                                        <div class="row">
                                            <div class="col-xs-6">Apostas: <b>€ {{number_format($bonus->bonus_wagered, 0, ' ', ' ')}}</b></div>
                                            <div class="col-xs-6">Bónus: <b>€ {{number_format($bonus->bonus_value, 0, ' ', ' ')}}</b></div>

                                            <div class="col-xs-6">Cota mínima: <b>{{$bonus->bonus->min_odd}}</b></div>
                                            <div class="col-xs-6">Criado em: <b>{{$bonus->created_at->format('Y-m-d')}}</b></div>

                                            <div class="col-xs-6">Objectivo: <b>€ {{number_format($bonus->rollover_amount, 0, ' ', ' ')}}</b></div>
                                            <div class="col-xs-6">Válido até: <b>{{$bonus->deadline_date->format('Y-m-d')}}</b></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="row">
            <div class="col-xs-12">
                <div class="texto">Não existem bónus ativos.</div>
            </div>
        </div>
    @endif
@stop

