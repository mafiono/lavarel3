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
</div>

<!---- CONTEND ---->
<div id="_apostas" class="main-contend black-back" style="min-width: 1204px; width: 100%">
    <div class="main-apostas" style="width: 99%; margin: 0 auto;">
        <!----- COLUNA 1 ------>
        @include('portal.bets.sports_menu')
        <!----- COLUNA 2 ------>
        @include('portal.bets.markets')
        <!----- COLUNA 3 ------>
        @include('portal.bets.betslip')
        <div class="clear"></div> <!-- fixes background size-->
    </div> <!-- END main-apostas -->
</div> <!-- END CONTEND -->
@stop
@section('scripts')

    <script src="assets/portal/js/spin.min.js" defer></script>
    <script src="assets/portal/js/handlebars/handlebars.min.js" defer></script>
    <script src="assets/portal/js/handlebars/handlebars.custom.js" defer></script>
    <script src="assets/portal/js/moment/moment.min.js" defer></script>
    <script src="assets/portal/js/moment/locale/pt.js" defer></script>
    <script src="assets/portal/js/js-cookie/js.cookie.min.js" defer></script>
    <script src="assets/portal/js/template.js" defer></script>

    <script src="assets/portal/js/sports/sportsPartials.js" defer></script>
    <script src="assets/portal/js/sports/marketsPartials.js" defer></script>
    <script src="assets/portal/js/sports/betslipPartials.js" defer></script>

    <script src="assets/portal/js/sports/SportsMenu.js" defer></script>
    <script src="assets/portal/js/sports/Markets.js" defer></script>
    <script src="assets/portal/js/sports/Betslip.js" defer></script>

@stop