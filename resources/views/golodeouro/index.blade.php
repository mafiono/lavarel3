@extends('layouts.portal', ['golodeouro' => true])

@section('styles')
    {!! HTML::style('assets/portal/css/casino.css?v='.config('app.rand_hash')) !!}
@stop

@section('content')
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
@stop

@section('scripts')
    <script>
        var userAuthenticated = {{ Auth::check() ? 'true' : 'false'}};
        var username = '{{ Auth::user()->username ?? ''}}';
        </script>
@stop