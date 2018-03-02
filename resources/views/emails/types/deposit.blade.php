@extends('emails.layouts.main')

@section('title')
    <h1>Olá <span>{{ $name }}</span>,</h1>
@stop

@section('message')
    <p>Foi efetuado com sucesso o depósito de {{$value}}€ na sua conta de jogador.</p>
    @if ($showExtra)
        <p>&nbsp;</p>
        <p>Ainda não tem a sua conta de pagamentos confirmada.</p>
        <p>&nbsp;</p>
        <p class="center"><a href="{{ $extraUrl }}" class="btn">CONFIRMAR CONTA PAGAMENTOS</a></p>
    @endif
    <p>&nbsp;</p>
    <p class="center"><a href="{{ $url }}" class="btn">COMECE A JOGAR</a></p>
    <p>&nbsp;</p>
    @include('emails.layouts.welcome')
@stop