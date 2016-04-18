@extends('layouts.portal')

@section('styles')
{!! HTML::style('assets/portal/css/sports/sports.css') !!}
{!! HTML::style('assets/portal/css/global.css') !!}
{!! HTML::style('assets/portal/css/sports/markets.css') !!}
{!! HTML::style('assets/portal/css/sports/bets.css') !!}
{!! HTML::style('assets/portal/css/favorites/favorites.css') !!}

{!! HTML::style('assets/portal/css/casino/casino.css') !!}
{!! HTML::style('assets/portal/css/owl.carousel/owl.carousel.css') !!}
{!! HTML::style('assets/portal/css/owl.carousel/owl.theme.css') !!}

@stop

@section('content')

<div id="_casino" class="casino-container hidden">
    <div id="casino-menu-container" class="casino-container-menu"></div>
    <div id="casino-content-container" class="casino-container-content clearfix"></div>
    {{--<div id="casino-all-container" class="casino-container-content clearfix">--}}
    {{--<div class="casino-container-header">--}}
    {{--<div class="acenter">--}}
    {{--<button class="casino-button">Casino</button>--}}
    {{--<button class="casino-button">Casino ao vivo</button>--}}
    {{--<button class="casino-button">Promoções</button>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--<div id="casino-featuredGames-container" class="casino-container-content hidden"></div>--}}
</div>

<!---- CONTEND ---->
<div id="_apostas" class="main-contend black-back" style="min-width: 1204px; width: 100%">
    <div class="main-apostas" style="width: 99%; margin: 0 auto;">
        <!----- COLUNA 1 ------>
        <div class=" fleft " style="width: 18%">
            <div class="apostas-box grey-back leftbar aleft">
                <div class="col-xs-12">
                    <div class="col-xs-45 jogo-box-botao blue-hover inline height1" id="live">Live</div>
                    <div class="col-xs-45 jogo-box-botao blue-hover inline height1" id="preMatch">Pré-Match</div>
                    <div class="col-xs-01 acenter inline height1"><i class="fa fa-chevron-left"></i></div>
                </div>
                <div class="col-xs-12 dyas-select">
                    <select id="events-period-select" class="col-xs-10 jogo-box-botao height2" style="text-align: left; text-indent: 15px;">
                        <option value="10000">Todas</option>
                        <option value="1">1 hora</option>
                        <option value="2">2 horas</option>
                        <option value="3">3 horas</option>
                        <option value="6">6 horas</option>
                        <option value="12">12 horas</option>
                        <option value="24">24 horas</option>
                        <option value="48">48 horas</option>
                        <option value="72">72 horas</option>
                    </select>
                </div>
                <div class="col-xs-12 apostas-modalidades">
                    <h2>Populares</h2>
                    <div id="left-menu-container">
                        <p style="position: relative; left: -20px; height: 120px;" id="leftSpinner"></p>
                    </div>
                </div>
            </div>
        </div>
        <!----- COLUNA 2 ------>
        @include('portal.bets.markets')
        <!----- COLUNA 3 ------>
        @include('portal.bets.bets')
        <div class="clear"></div> <!-- fixes background size-->
    </div> <!-- END main-apostas -->
</div> <!-- END CONTEND -->
@stop
@section('scripts')



    <script type="text/javascript">
        var phpAuthUser = {!! json_encode($phpAuthUser) !!};
    </script>

    {!! HTML::script('assets/portal/js/route/route.js') !!}
    {!! HTML::script('assets/portal/js/route/routeCustom.js') !!}

    {!! HTML::script('assets/portal/js/handlebars/handlebars.min.js') !!}
    {!! HTML::script('assets/portal/js/moment/moment.min.js') !!}
    {!! HTML::script('assets/portal/js/moment/locale/pt.js') !!}
    {!! HTML::script('assets/portal/js/js-cookie/js.cookie.min.js') !!}
    {!! HTML::script('assets/portal/js/template.js') !!}
    {!! HTML::script('assets/portal/js/eventHandler.js') !!}
    {!! HTML::script('assets/portal/js/sports/sportsBarController.js') !!}

    {!! HTML::script('assets/portal/js/sports/marketsController.js') !!}
    {!! HTML::script('assets/portal/js/favorites/favorites.js') !!}
    {!! HTML::script('assets/portal/js/favorites/favoritesController.js') !!}
    {!! HTML::script('assets/portal/js/sports/betsGraveward.js') !!}
    {!! HTML::script('assets/portal/js/sports/betsService.js') !!}
    {!! HTML::script('assets/portal/js/sports/betsplip.js') !!}
    {!! HTML::script('assets/portal/js/sports/betsController.js') !!}
    {!! HTML::script('assets/portal/js/sports/main.js') !!}
    {!! HTML::script('assets/portal/js/owl.carousel/owl.carousel.min.js') !!}
    {!! HTML::script('assets/portal/js/casino/casinoController.js') !!}

    {!! HTML::script('assets/portal/js/spin.min.js') !!}
    {!! HTML::script('assets/portal/js/jquery.spin.js') !!}

@stop