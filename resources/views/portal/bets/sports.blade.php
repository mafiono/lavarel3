@extends('layouts.portal')

@section('styles')
    {!! HTML::style('assets/portal/css/sports.css') !!}
@stop

@section('content')

<div id="_casino" class="casino-container hidden">
    <div id="casino-menu-container" class="casino-container-menu"></div>
    <div id="casino-content-container" class="casino-container-content clearfix"></div>
</div>

<div id="terminalVerifier-container" class="hidden"></div>

<!---- CONTEND ---->
<div id="_apostas" class="main-contend">
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
    <script>
        var userAuthenticated = {{is_null($authUser) ? 'false' : 'true'}};
        var username = "{{ $authUser->username ?? ''}}";
    </script>

    <script src="/assets/portal/js/app.js"></script>

    <script>
        var ODDS_SERVER = "{{config('app.odds_server')}}";

        var PopularSportsMenu = new SportsMenu({
            container: $("#sportsMenu-popular"),
            refreshInterval: 1800
        });

        LeftMenu.makeHighlights({!! $competitions !!});
        PopularSportsMenu.make();
        HighFixtures.make({ highGameIds: {!! $games !!}});

        setInterval(function () {

            if(  $( "#live-football-container").html() == '' && $("#live-basketball-container").html() == ''  &&  $("#live-tenis-container").html() == ''   )
            {

                $( "#live-football-container").html('<div class="markets-unavailable"> <p>De momento não existe qualquer evento disponível em direto.</p> </div>')

            }
        }, 1000);
    </script>

@stop