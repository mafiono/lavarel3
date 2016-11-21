<?php
if (!isset($input)) {
    $input = null;
}
?>
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
                <div class="profile bs-wp">
                    <div class="header">
                        DADOS DE CONTA
                        <i id="info-close" class="fa fa-times"></i>
                        <span class="user_id">ID: {{Auth::user()->id}}</span>
                    </div>
                    <div class="top-nav">
                        @include('portal.profile.head', ['active' => $active1])
                    </div>
                    <div class="profile-sidebar">
                        @include($middle, ['active' => $active2, 'input' => $input])
                    </div>
                    <div class="profilecontent" style="overflow:auto;">
                        @if (isset($form))
                            {!! Form::open($form) !!}
                        @endif

                        @yield('sub-content')

                        <div class="profile-button-right">
                            @if (isset($form))
                                <input type="submit" value="{{$btn or 'Guardar'}}"/>
                            @endif
                        </div>
                        @if (isset($form))
                            {!! Form::close() !!}
                        @endif
                    </div>
                    <div class="profile-footer">
                        <div class="line"></div>
                    </div>
                </div>
            </div>
            <!----- COLUNA 3 ------>
            @include('portal.bets.betslip')
            <div class="clear"></div> <!-- fixes background size-->
        </div> <!-- END main-apostas -->
    </div> <!-- END CONTEND -->

@stop
@section('box.scripts')

    {!! HTML::script(URL::asset('/assets/portal/js/router/page.js')) !!}
    {!! HTML::script(URL::asset('/assets/portal/js/plugins/jQuery.print.js')) !!}

    {!! HTML::script(URL::asset('/assets/portal/js/spin.min.js')) !!}
    {!! HTML::script(URL::asset('/assets/portal/js/handlebars/handlebars.min.js')) !!}
    {!! HTML::script(URL::asset('/assets/portal/js/handlebars/handlebars.custom.js')) !!}
    {!! HTML::script(URL::asset('/assets/portal/js/moment/moment.min.js')) !!}
    {!! HTML::script(URL::asset('/assets/portal/js/moment/locale/pt.js')) !!}
    {!! HTML::script(URL::asset('/assets/portal/js/js-cookie/js.cookie.min.js')) !!}
    {!! HTML::script(URL::asset('/assets/portal/js/template.js')) !!}

    {!! HTML::script(URL::asset('/assets/portal/js/app.js')) !!}

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

        $('#info-close').click(function(){

            top.location.replace("/");
        });

    </script>
@stop