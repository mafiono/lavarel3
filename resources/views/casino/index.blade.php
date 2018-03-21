@extends('layouts.portal', ['casino' => true])

@section('styles')
    {!! HTML::style('assets/portal/css/casino.css?v='.config('app.rand_hash')) !!}
@stop

@section('content')
    <div class="casino">

        <transition mode="out-in" name="vue-fade">
            <div class="sidebar">
                <banner href="/promocoes" image="/assets/portal/img/casino/banners/bonus-888a.jpg" alt="Adira já!"></banner>
                <left-menu></left-menu>
            </div>
        </transition>
        <div class="main-content">
            <transition mode="out-in" name="vue-fade">
                <slider></slider>
            </transition>
            <transition mode="out-in" name="vue-fade">
                <keep-alive>
                    <router-view></router-view>
                </keep-alive>
            </transition>
            <transition mode="out-in" name="vue-fade">
                <featured></featured>
            </transition>
        </div>
    </div>
@stop

@section('scripts')
    <script>
        var RAND_HASH = '{{ config('app.rand_hash') }}';
        var SLIDE_GAMES = [1560,4737,209030,4612,];
        var userAuthenticated = {{ Auth::check() ? 'true' : 'false'}};
        var username = '{{ Auth::user()->username ?? ''}}';

        var games = {!!$games!!};

        $(function () {
           $("#header-casino").addClass("active");
        });
    </script>
    <script src="/assets/portal/js/casino.js?v={{config('app.rand_hash')}}"></script>
@stop