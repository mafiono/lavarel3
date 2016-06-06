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
        @include('portal.bets.sports_menu')
        <!----- COLUNA 2 ------>
        @include('portal.bets.markets')
        <!----- COLUNA 3 ------>
        @include('portal.bets.bets')
        <div class="clear"></div> <!-- fixes background size-->
    </div> <!-- END main-apostas -->
</div> <!-- END CONTEND -->
@stop
@section('scripts')



    {{--<script type="text/javascript">--}}
        {{--var phpAuthUser = {!! json_encode($phpAuthUser) !!};--}}
    {{--</script>--}}

    {!! HTML::script('assets/portal/js/sports/SportsMenu.js') !!}
    {!! HTML::script('assets/portal/js/sports/Markets.js') !!}
    {{--{!! HTML::script('assets/portal/js/route/route.js') !!}--}}
    {{--{!! HTML::script('assets/portal/js/route/routeCustom.js') !!}--}}

    {!! HTML::script('assets/portal/js/handlebars/handlebars.min.js') !!}
    {!! HTML::script('assets/portal/js/moment/moment.min.js') !!}
    {!! HTML::script('assets/portal/js/moment/locale/pt.js') !!}
    {!! HTML::script('assets/portal/js/js-cookie/js.cookie.min.js') !!}
    {!! HTML::script('assets/portal/js/template.js') !!}

    {{--{!! HTML::script('assets/portal/js/eventHandler.js') !!}--}}
    {{--{!! HTML::script('assets/portal/js/sports/sportsBarController.js') !!}--}}

    {{--{!! HTML::script('assets/portal/js/sports/marketsController.js') !!}--}}
    {{--{!! HTML::script('assets/portal/js/favorites/favorites.js') !!}--}}
    {{--{!! HTML::script('assets/portal/js/favorites/favoritesController.js') !!}--}}
    {{--{!! HTML::script('assets/portal/js/sports/betsGraveward.js') !!}--}}
    {{--{!! HTML::script('assets/portal/js/sports/betsService.js') !!}--}}
    {{--{!! HTML::script('assets/portal/js/sports/betsplip.js') !!}--}}
    {{--{!! HTML::script('assets/portal/js/sports/betsController.js') !!}--}}
    {!! HTML::script('assets/portal/js/sports/main.js') !!}
    {{--{!! HTML::script('assets/portal/js/owl.carousel/owl.carousel.min.js') !!}--}}
    {{--{!! HTML::script('assets/portal/js/casino/casinoController.js') !!}--}}

    {!! HTML::script('assets/portal/js/spin.min.js') !!}
    {{--{!! HTML::script('assets/portal/js/jquery.spin.js') !!}--}}

@stop