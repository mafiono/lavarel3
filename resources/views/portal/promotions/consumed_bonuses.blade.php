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
                                <div class="col-xs-3 text-center">{{$bonus->bonus->bonusType->name}}</div>
                                <div class="col-xs-3 text-center">€ {{((float) $bonus->bonus->value).($bonus->bonus->value_type==='percentage'?'%':'')}}</div>
                                <div class="col-xs-3 text-right">{{$bonus->created_at->format('Y-m-d')}}</div>
                                <div class="col-xs-1 text-center"><i class="fa fa-exclamation-circle fa-2x"></i></div>
                                <div class="bag">
                                    <div class="details">
                                        <div class="row">
                                            <div class="col-xs-6">Depósito mínimo: <b>€ {{number_format($bonus->bonus->min_deposit, 0, ' ', ' ')}}</b></div>
                                            <div class="col-xs-6">Depósito máximo: <b>€ {{number_format($bonus->bonus->max_deposit, 0, ' ', ' ')}}</b></div>

                                            <div class="col-xs-6">Cota mínima: <b>{{$bonus->bonus->min_odd}}</b></div>
                                            <div class="col-xs-6">Válido durante: <b>{{$bonus->bonus->deadline}} dias</b></div>

                                            <div class="col-xs-12">Montante apostado: <b>{{number_format($bonus->bonus->rollover_coefficient, 0, ' ', ' ')}} x valor depósito + valor bónus</b></div>
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
                <div class="texto">Não existe promoções ou bónus de Desporto utílizados</div>
            </div>
        </div>
    @endif
@stop

@section('scripts')
    <script>
        $('.bonus i.fa-2x').click(function () {
            var self = $(this);
            self.parents('.row').toggleClass('active');
        });
    </script>
@stop