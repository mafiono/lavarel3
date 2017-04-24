@extends('portal.profile.layout', [
    'active1' => 'promocoes',
    'middle' => 'portal.promotions.head_promotions',
    'active2' => 'por_utilizar'])

@section('sub-content')
    <div class="row">
        <div class="col-xs-12">
            <div class="title">Promoções e Bónus disponíveis</div>
        </div>
    </div>

    @if(count($availableSportBonuses) > 0 || count($availableCasinoBonuses) > 0)
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
                    <div class="texto">Não existem promoções ou bónus de desporto disponíveis.</div>
                </div>
            </div>
        @endif
        @if(count($availableCasinoBonuses) > 0)
            <div class="row">
                <div class="col-xs-12">
                    <div class="title"></div>
                </div>
            </div>
            <div class="bonus table-like">
                <div class="row header">
                    <div class="col-xs-6">Casino</div>
                    <div class="col-xs-2 text-center">Bónus</div>
                    <div class="col-xs-4"></div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <div class="place"></div>
                    </div>
                </div>
            </div>
        @else
            <div class="row">
                <div class="col-xs-12">
                    <div class="texto">Não existem promoções ou bónus de casino disponíveis.</div>
                </div>
            </div>
        @endif
    @else
        <div class="row">
            <div class="col-xs-12">
                <div class="texto">Não existem promoções ou bónus disponíveis.</div>
            </div>
        </div>
    @endif
@stop