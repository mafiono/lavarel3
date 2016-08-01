@extends('layouts.portal')

@section('styles')
<link href='https://fonts.googleapis.com/css?family=Exo+2:400,700|Open+Sans:400,400italic,700italic,700' rel='stylesheet' type='text/css'>

{!! HTML::style('assets/portal/css/global.css') !!}
{!! HTML::style('assets/portal/css/sports.css') !!}

@stop

@section('content')

<div id="_casino" class="casino-container hidden">
    <div id="casino-menu-container" class="casino-container-menu"></div>
    <div id="casino-content-container" class="casino-container-content clearfix"></div>
</div>

<!---- CONTEND ---->
<div id="_apostas" class="main-contend" style="width: 1200px; margin: 120px auto 20px">
    <div class="main-apostas">
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

    <script src="/assets/portal/js/router/page.js" ></script>
    <script src="/assets/portal/js/plugins/jQuery.print.js" ></script>

    <script src="/assets/portal/js/spin.min.js" ></script>
    <script src="/assets/portal/js/handlebars/handlebars.min.js" ></script>
    <script src="/assets/portal/js/handlebars/handlebars.custom.js" ></script>
    <script src="/assets/portal/js/moment/moment.min.js" ></script>
    <script src="/assets/portal/js/moment/locale/pt.js" ></script>
    <script src="/assets/portal/js/js-cookie/js.cookie.min.js" ></script>
    <script src="/assets/portal/js/template.js" ></script>

    <script src="/assets/portal/js/sports.js" ></script>

    <script>
        var ODDS_SERVER = "http://genius.ibetup.eu/";

        var PopularSportsMenu = new SportsMenu({
            container: $("#sportsMenu-popular")
        });

        $(function () {
            LeftMenu.makeHighlights([
                @foreach($competitions as $competition)
                    {{$competition->highlight_id}},
                @endforeach
            ]);

            PopularSportsMenu.make();
        });

    </script>

@stop