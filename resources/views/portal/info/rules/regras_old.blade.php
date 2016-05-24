@extends('layouts.faqs')

@section('content')
    @include('portal.info.rules.menu')
    <div class="middle">
        @include('portal.info.rules.'.$tipo.'.menu')
        <div class="text">
            @include('portal.info.rules.'.$tipo.'.'.$game)
        </div>
    </div>
    <div class="clear"></div>
@stop
