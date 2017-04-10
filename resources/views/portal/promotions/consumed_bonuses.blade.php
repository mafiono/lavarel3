@extends('portal.profile.layout', [
    'active1' => 'promocoes',
    'middle' => 'portal.promotions.head_promotions',
    'active2' => 'utilizados'])

@section('sub-content')

    <div class="row">
        <div class="col-xs-12">
            <div class="title">Promoções e Bónus</div>
        </div>
    </div>

    @if(count($consumedSportBonuses) > 0)
        <div class="bonus table-like">
            <div class="row header">
                <div class="col-xs-2">Origem</div>
                <div class="col-xs-3 text-center">Tipo</div>
                <div class="col-xs-3 text-center">Bónus</div>
                <div class="col-xs-3 text-right">Data</div>
                <div class="col-xs-1"></div>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <div class="place">
                        @foreach($consumedSportBonuses as $bonus)
                            <div class="row">
                                <div class="col-xs-2">{{trans('bonus.origin.'.$bonus->bonus->bonus_origin_id)}}</div>
                                <div class="col-xs-3 text-center">{{$bonus->bonus->title}}</div>
                                <div class="col-xs-3 text-center">€ {{number_format($bonus->bonus_value, 0, ' ', ' ')}}</div>
                                <div class="col-xs-3 text-right">{{$bonus->created_at->format('Y-m-d')}}</div>
                                <div class="col-xs-1 text-center"><i class="cp-exclamation-circle cp-2x"></i></div>
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
                <div class="texto">Não tem quaisquer promoções ou bónus utilizados</div>
            </div>
        </div>
    @endif
@stop