@extends('layouts.portal', ['casino' => false])

@section('styles')
    {!! HTML::style('assets/portal/css/sports.css?v='.config('app.rand_hash')) !!}

   
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

        <div class="bs-wp">
            <div class="col-xs-12 col-md-3">
        <div id="stats" class="stats bs-wp hidden-xs" style="height:800px;">
            <iframe id="statsgolo" style="height:647px; width:300px; border:none;overflow:hidden;"></iframe>
        </div>
        </div>
        </div>
        <div class="clear"></div> <!-- fixes background size-->
    </div> <!-- END main-apostas -->
</div> <!-- END CONTEND -->
@stop
@section('scripts')
    <script>
        var userAuthenticated = {{is_null($authUser) ? 'false' : 'true'}};
        var username = '{{ $authUser->username ?? ''}}';
    </script>

    <script src="/assets/portal/js/app.js?v={{config('app.rand_hash')}}"></script>

    <script>
        var ODDS_SERVER = "{{config('app.odds_server')}}";

        var PopularSportsMenu = new SportsMenu({
            container: $("#sportsMenu-popular"),
            refreshInterval: 1800
        });

        LeftMenu.makeHighlights({!! $highlights !!});
        PopularSportsMenu.make();
        HighFixtures.make({ highGameIds: {!! $games !!}});

        setInterval(function () {
            if($("#live-football-container").html() == '' && $("#live-basketball-container").html() == '' && $("#live-tenis-container").html() == '')
            {
                $( "#live-football-container").html('<div class="markets-unavailable"> <p>Neste momento não existem eventos em direto!</p> </div>')
            }
        }, 1000);
    </script>

    @if (session()->has('bets'))
        <script>
            $(function() {
                Betslip.addSelections({!! json_encode(session('bets')) !!});
            });
        </script>
    @endif
@stop