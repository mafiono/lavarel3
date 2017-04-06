@extends('emails.layouts.main')

@section('title')
    <h1>Olá <span>{{ $name }}</span>,</h1>
@endsection

@section('message')
    <p>Foi efetuado com sucesso o pagamento de {{$value}}€ na sua conta bancária.</p>
    <p>&nbsp;</p>
    <p>Mantenha-se a par de todas as nossas promoções!</p>
    <p>&nbsp;</p>
    <p class="center"><a href="{{ $url }}" class="btn">PROMOÇÕES</a></p>
    <p>&nbsp;</p>
    @include('emails.layouts.welcome')
@endsection