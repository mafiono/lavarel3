@extends('emails.layouts.main')

@section('title')
    <h1>Olá <span>{{ $user->username }}</span>,</h1>
@endsection

@section('message')
    <p>Bem-vindo ao Casino Portugal!</p>
    <p>&nbsp;</p>
    <p>A sua identidade foi confirmada com sucesso.</p>
    <p>Agora já pode apostar nos seus jogos favoritos, basta confirmar o seu email.</p>
    <p>&nbsp;</p>
    <p class="center"><a href="{{ $url }}" class="btn">CONFIRMAR</a></p>
    <p>&nbsp;</p>
    @include('emails.layouts.welcome')
@endsection