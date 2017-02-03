@extends('layouts.portal')

@section('styles')
    <link href='https://fonts.googleapis.com/css?family=Exo+2:400,700|Open+Sans:400,400italic,700italic,700' rel='stylesheet' type='text/css'>

    {!! HTML::style('assets/portal/css/casino.css') !!}
@stop

@section('content')
    <div id="terminalVerifier-container" class="hidden"></div>
    <div class="casino">

        <transition appear>
            <div class="sidebar">
                <banner href="#" image="/assets/portal/img/casino/banners/banner1.png" alt="promo"></banner>
                <left-menu></left-menu>
            </div>
        </transition>
        <div class="main-content">
            <transition appear>
                <slider></slider>
            </transition>
            <transition appear>
                <keep-alive>
                    <router-view></router-view>
                </keep-alive>
            </transition>
            <transition appear>
                <featured></featured>
            </transition>
        </div>
    </div>

@stop

@section('scripts')
    <script>
        var userLoggedIn = {{Auth::check()? 'true' : 'false'}};

        var games = {!!$games!!};

        $(function () {
           $("#header-casino").addClass("active");
        });
    </script>
    <script src="/assets/portal/js/casino.js"></script>
@stop