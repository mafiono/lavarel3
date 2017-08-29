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

    @if((count($availableSportBonuses) + count($availableCasinoBonuses)) > 0)
        @if(count($availableSportBonuses) > 0 )
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
                                    <div class="col-xs-3 text-center button"><a href="/bonus/sport/redeem/{{$bonus->id}}" class="btn btn-success redeem"
                                                                                data-id="{{$bonus->id}}" data-title="{{$bonus->title}}">Utilizar</a></div>
                                    <div class="bag">
                                        <div class="details">
                                            <div class="row">
                                                <div class="col-xs-12">Bónus: <b>€ {{number_format(SportsBonus::previewRedeemAmount($bonus), 0, ' ', ' ')}}</b></div>
                                                <div class="col-xs-12">Cota mínima: <b>{{$bonus->min_odd}}</b></div>
                                                <div class="col-xs-12">Válido durante: <b>{{$bonus->deadline}} dias</b></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if(count($availableSportBonuses) > 0 )
            <div class="bonus table-like">
                <div class="row header">
                    <div class="col-xs-7">Casino</div>
                    <div class="col-xs-2 text-center">Bónus</div>
                    <div class="col-xs-3"></div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <div class="place">
                            @foreach($availableCasinoBonuses as $bonus)
                                <div class="row">
                                    <div class="col-xs-7">{{$bonus->title}}</div>
                                    <div class="col-xs-2 text-center"><i class="cp-exclamation-circle cp-2x"></i></div>
                                    <div class="col-xs-3 text-center button"><a href="/bonus/casino/redeem/{{$bonus->id}}" class="btn btn-success redeem"
                                                                                data-id="{{$bonus->id}}" data-title="{{$bonus->title}}">Utilizar</a></div>
                                    <div class="bag">
                                        <div class="details">
                                            <div class="row">
                                                <div class="col-xs-12">Bónus: <b>€ {{number_format(CasinoBonus::previewRedeemAmount($bonus), 0, ' ', ' ')}}</b></div>
                                                <div class="col-xs-12">Cota mínima: <b>{{$bonus->min_odd}}</b></div>
                                                <div class="col-xs-12">Válido durante: <b>{{$bonus->deadline}} dias</b></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endif
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

    @if((count($activeSportBonuses) + count($activeCasinoBonuses)) > 0)
        @if(count($activeSportBonuses) > 0)
            <div class="bonus table-like">
                <div class="row header">
                    <div class="col-xs-5">Desportos</div>
                    <div class="col-xs-3 text-center">Bónus</div>
                    <div class="col-xs-3 text-center">Apostas</div>
                    <div class="col-xs-2"></div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <div class="place">
                            @foreach($activeSportBonuses as $bonus)
                                <div class="row">
                                    <div class="col-xs-4">{{$bonus->bonus->title}}</div>
                                    <div class="col-xs-1 text-center"><i class="cp-exclamation-circle cp-2x"></i></div>
                                    <div class="col-xs-3 text-center">€ {{number_format($bonus->bonus_value, 2, ' ', ' ')}}</div>
                                    <div class="col-xs-3 text-center">€ {{$bonus->balance_bonus}}</div>
                                    <div class="col-xs-1 text-center button">
                                        <a href="/bonus/sport/cancel/{{$bonus->id}}" class="cp-2x cp-times cancel"
                                           data-id="{{$bonus->id}}" data-title="{{$bonus->bonus->title}}"></a>
                                    </div>
                                    <div class="bag">
                                        <div class="details">
                                            <div class="row">
                                                <div class="col-xs-6">Apostas: <b>€ {{number_format($bonus->bonus_wagered, 2, ' ', ' ')}}</b></div>
                                                <div class="col-xs-6">Bónus: <b>€ {{number_format($bonus->bonus_value, 2, ' ', ' ')}}</b></div>

                                                <div class="col-xs-6">Cota mínima: <b>{{$bonus->bonus->min_odd}}</b></div>
                                                <div class="col-xs-6">Criado em: <b>{{$bonus->created_at->format('Y-m-d')}}</b></div>

                                                <div class="col-xs-12">Válido até: <b>{{$bonus->deadline_date->format('Y-m-d')}}</b></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if(count($activeCasinoBonuses) > 0)
            <div class="bonus table-like">
                <div class="row header">
                    <div class="col-xs-5">Casino</div>
                    <div class="col-xs-3 text-center">Bónus</div>
                    <div class="col-xs-3 text-center">Apostas</div>
                    <div class="col-xs-2"></div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <div class="place">
                            @foreach($activeCasinoBonuses as $bonus)
                                <div class="row">
                                    <div class="col-xs-4">{{$bonus->bonus->title}}</div>
                                    <div class="col-xs-1 text-center"><i class="cp-exclamation-circle cp-2x"></i></div>
                                    <div class="col-xs-3 text-center">€ {{number_format($bonus->bonus_value, 2, ' ', ' ')}}</div>
                                    <div class="col-xs-3 text-center">€ {{$bonus->balance_bonus}}</div>
                                    <div class="col-xs-1 text-center button">
                                        <a href="/bonus/casino/cancel/{{$bonus->id}}" class="cp-2x cp-times cancel"
                                           data-id="{{$bonus->id}}" data-title="{{$bonus->bonus->title}}"></a>
                                    </div>
                                    <div class="bag">
                                        <div class="details">
                                            <div class="row">
                                                <div class="col-xs-6">Apostas: <b>€ {{number_format($bonus->bonus_wagered, 2, ' ', ' ')}}</b></div>
                                                <div class="col-xs-6">Bónus: <b>€ {{number_format($bonus->bonus_value, 2, ' ', ' ')}}</b></div>

                                                <div class="col-xs-6">Cota mínima: <b>{{$bonus->bonus->min_odd}}</b></div>
                                                <div class="col-xs-6">Criado em: <b>{{$bonus->created_at->format('Y-m-d')}}</b></div>

                                                <div class="col-xs-12">Válido até: <b>{{$bonus->deadline_date->format('Y-m-d')}}</b></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @else
        <div class="row">
            <div class="col-xs-12">
                <div class="texto">Não existem bónus ativos.</div>
            </div>
        </div>
    @endif
@stop

