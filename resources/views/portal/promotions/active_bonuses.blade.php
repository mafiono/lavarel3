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
                                <div class="col-xs-3">{{$bonus->bonus->title}}</div>
                                <div class="col-xs-1 text-center"><i class="fa fa-exclamation-circle fa-2x"></i></div>
                                <div class="col-xs-2 text-center">{{($bonus->bonus->value_type==='percentage'?'':'€ ').((float) $bonus->bonus->value).($bonus->bonus->value_type==='percentage'?'%':'')}}</div>
                                <div class="col-xs-2 text-center">€ {{$bonus->balance_bonus}}</div>
                                <div class="col-xs-3 text-center">€ {{number_format($bonus->rollover_amount, 0, ' ', ' ')}}</div>
                                <div class="col-xs-1 text-center button">
                                    <a href="/promocoes/cancel/{{$bonus->id}}" class="fa fa-2x fa-remove"
                                       onclick="return confirmCancel('{{$bonus->id}}','{{$bonus->bonus->title}}')"></a>
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
                <div class="texto">Não existe promoções ou bónus de Desporto ativos</div>
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
        function confirmCancel(id, title) {
            $.fn.popup({
                title: 'Bónus',
                text: 'Tem a certeza que pretende cancelar o ' + title + '?',
                type: "warning",
                showCancelButton: true,
                confirmButtonText: "Sim",
                cancelButtonText: "Não",
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