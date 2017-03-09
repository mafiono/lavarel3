@extends('emails.layouts.main')

@section('title')
    <h1>Olá <span>{{ $user->username }}</span>,</h1>
@endsection

@section('message')
    <p>Registámos o seu pedido de levantamento de {{$value}}€ da sua conta de jogador.</p>
    <p>&nbsp;</p>
    <p>Caso não tenho solicitado este levantamento contacte o nosso <a href="mailto:apoiocliente@casinoportugal.pt" class="link">apoio ao cliente</a>.</p>
    <p>&nbsp;</p>
    <p class="center"><a href="{{ $url }}" class="btn">COMECE A JOGAR</a></p>
    <p>&nbsp;</p>
    @include('emails.layouts.welcome')
@endsection