@extends('layouts.faqs')

@section('content')
    @include('portal.info.rules.menu')
    <div class="middle">
        <ul class="sub-menu">
            <li {{$game=='index'?'class=sel':''}}><a href="/info/regras/{{$tipo}}">Regras</a></li>
            @foreach($childes as $item)
                <li {{$game==$item['game']?'class=sel':''}}><a href="/info/regras/{{$tipo}}/{{$item['game']}}">{{$item['name']}}</a></li>
            @endforeach
        </ul>
        <div class="text">
            {!! $legalDoc->description or 'rules.'.$tipo.'.'.$game !!}
        </div>
    </div>
    <div class="clear"></div>
@stop
