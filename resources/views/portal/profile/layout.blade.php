@extends('layouts.portal')
@section('styles')
    <link href='https://fonts.googleapis.com/css?family=Exo+2:400,700|Open+Sans:400,400italic,700italic,700' rel='stylesheet' type='text/css'>

    {!! HTML::style('assets/portal/css/profile.css') !!}
    {!! HTML::style('assets/portal/css/sports.css') !!}
    {!! HTML::style('assets/portal/css/global.css') !!}



@stop
@section('content')
    <div id="_casino" class="casino-container hidden">
        <div id="casino-menu-container" class="casino-container-menu"></div>
        <div id="casino-content-container" class="casino-container-content clearfix"></div>
    </div>

    <div id="terminalVerifier-container" class="hidden"></div>

    <!---- CONTEND ---->
    <div id="_apostas" class="main-contend" style="width: 1200px; margin: 120px auto 20px">
        <div class="main-apostas">
            <!----- COLUNA 1 ------>
        @include('portal.bets.sports_menu')
        <!----- COLUNA 2 ------>
            <div class="markets-container" style="height:888px;">
                <div class="profile">
                            <div class="header1">
                                DADOS DE CONTA
                                <i id="info-close" class="fa fa-times"></i>
                            </div>
                                <div class="header">
                            @include('portal.profile.head', ['active' => $active1])
                                </div>
                                            <div class="profilesidebar">
                                            <?php if (! isset($input)) { $input = null; } ?>
                                            @include($middle, ['active' => $active2, 'input' => $input])
                                            </div>
                                <div class="profilecontent" style="overflow:auto;">
                                    @if (isset($form))
                                        {!! Form::open($form) !!}
                                    @endif

                                        @yield('sub-content')


                                                    <div class="profile-button-right">
                                                        @if (isset($form))
                                                            <input type="submit"  value="{{$btn or 'Guardar'}}" />
                                                        @endif

                                                    </div>

                                    @if (isset($form))
                                        {!! Form::close() !!}
                                    @endif

                                </div>


                </div>
            </div>
        <!----- COLUNA 3 ------>
            @include('portal.bets.betslip')
            <div class="clear"></div> <!-- fixes background size-->
        </div> <!-- END main-apostas -->
    </div> <!-- END CONTEND -->


    <script src="/assets/portal/js/router/page.js" ></script>
    <script src="/assets/portal/js/plugins/jQuery.print.js" ></script>

    <script src="/assets/portal/js/spin.min.js" ></script>
    <script src="/assets/portal/js/handlebars/handlebars.min.js" ></script>
    <script src="/assets/portal/js/handlebars/handlebars.custom.js" ></script>
    <script src="/assets/portal/js/moment/moment.min.js" ></script>
    <script src="/assets/portal/js/moment/locale/pt.js" ></script>
    <script src="/assets/portal/js/js-cookie/js.cookie.min.js" ></script>
    <script src="/assets/portal/js/template.js"></script>

    <script src="/assets/portal/js/app.js"></script>

    <script>
        var ODDS_SERVER = "{{config('app.odds_server')}}";

        var PopularSportsMenu = new SportsMenu({
            container: $("#sportsMenu-popular")
        });

        $(function () {

            $.get( "/api/competitions", function( data ) {
                $.each(data, function(i, item) {
                   
                    LeftMenu.makeHighlights([data[i].highlight_id

                    ]);

                    PopularSportsMenu.make();
                }) })});

    </script>

@stop
@section('scripts')





@stop

<!---- FIM CONTENT ---->
