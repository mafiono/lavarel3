@extends('emails.layouts.main')

@section('title')
    <h1>Olá <span>{{ $user->username }}</span>,</h1>
@endsection

@section('message')
    <p>A sua aposta nº{{$nr}}, no valor de {{$value}}€, foi devolvida por motivos alheios ao Casino Portugal. </p>
    <p>&nbsp;</p>
    <p>Caso não tenho solicitado este levantamento contacte o nosso <a href="mailto:apoiocliente@casinoportugal.pt" class="link">apoio ao cliente</a>.</p>
    <p>&nbsp;</p>
    @include('emails.layouts.welcome')
@endsection