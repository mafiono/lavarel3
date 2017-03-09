@extends('emails.layouts.main')

@section('title')
    <h1>Olá <span>{{ $user->username }}</span>,</h1>
@endsection

@section('message')
    <p>A sua identidade foi adicionada com sucesso.
        Agora vai poder começar a apostar nos seus jogos favoritos, basta apenas confirmar o seu email:</p>
    <p>&nbsp;</p>
    <p class="center"><a href="{{ $url }}" class="btn">{{$button}}</a></p>
    <p>&nbsp;</p>
    <p>E para celebrar a sua adesão temos o prazer em oferecer-lhe um bónus especial que irá ser disponibilizado na sua conta após esta confirmação.</p>
    <p>&nbsp;</p>
    @include('emails.layouts.welcome')
@endsection