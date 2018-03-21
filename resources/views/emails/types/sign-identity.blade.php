@extends('emails.layouts.main')

@section('title')
    <h1>Olá <span>{{ $name }}</span>,</h1>
@stop

@section('message')
    <p>Bem-vindo ao Casino Portugal!</p>
    <p>&nbsp;</p>
    <p>Para começar a apostar nos seus jogos favoritos, basta enviar um comprovativo de identidade.</p>
    <p>&nbsp;</p>
    <p class="center"><a href="{{ $url }}" class="btn">ENVIAR COMPROVATIVO</a></p>
    <p>&nbsp;</p>
    @include('emails.layouts.welcome')
@stop