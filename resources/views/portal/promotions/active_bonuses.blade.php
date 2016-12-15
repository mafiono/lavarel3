@extends('portal.profile.layout', [
    'active1' => 'promocoes',
    'middle' => 'portal.promotions.head_promotions',
    'active2' => 'activos'])

@section('sub-content')

    <div class="row">
        <div class="col-xs-12">
            <div class="title">Promoções e Bónus ativos</div>
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
                                <div class="col-xs-4">{{$bonus->bonus->title}}</div>
                                <div class="col-xs-2 text-center">{{($bonus->bonus->value_type==='percentage'?'':'€ ').((float) $bonus->bonus->value).($bonus->bonus->value_type==='percentage'?'%':'')}}</div>
                                <div class="col-xs-2 text-center">€ {{$bonus->balance_bonus}}</div>
                                <div class="col-xs-3 text-center">€ {{$bonus->rollover_amount}}</div>
                                <div class="col-xs-1 text-center button">
                                    <a href="/promocoes/cancel/{{$bonus->id}}" class="fa fa-2x fa-remove"
                                       onclick="return confirmCancel('{{$bonus->id}}','{{$bonus->bonus->title}}')"></a>
                                </div>
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
                <div class="texto">Não existe promoções ou bónus de Desporto ativos</div>
            </div>
        </div>
    @endif
@stop

@section('scripts')
    <script>
        $('.bonus .fa-2x').click(function () {
            var self = $(this);
            self.parents('.row').toggleClass('active');
        });
        function confirmCancel(id, title) {
            $.fn.popup({
                title: 'Bónus',
                text: 'Tem a certeza que pretende cancelar o ' + title + '?',
                type: "warning",
                showCancelButton: true,
                confirmButtonText: "Cancelar",
                cancelButtonText: "Cancelar",
                closeOnConfirm: false,
                closeOnCancel: false
            }, function (confirmed) {
                if (confirmed) {
                    $.get('/promocoes/cancel/' + id)
                        .success(function () {
                            $.fn.popup({
                                title: 'Bónus',
                                text: 'Bónus ' + title + ' cancelado com sucesso!',
                                type: "success"
                            }, function () {
                                window.location.reload();
                            });
                        })
                        .error(function (obj) {
                            var response = obj.responseJSON;
                            $.fn.popup({
                                title: response.title || '&nbsp;',
                                text: response.msg,
                                type: 'error'
                            });
                        })
                        .done();
                } else {
                    swal.close();
                }
            });
            return false;
        }
    </script>
@stop