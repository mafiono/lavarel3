@extends('emails.layouts.main')

@section('title')
    <h1>Olá <span>{{ $name }}</span>,</h1>
@endsection

@section('message')
    <p>Foi efetuado com sucesso o depósito de {{$value}}€ na sua conta de jogador.</p>
    <p>&nbsp;</p>
    <p class="center"><a href="{{ $url }}" class="btn">COMECE A JOGAR</a></p>
    <p>&nbsp;</p>
    @include('emails.layouts.welcome')
@endsection