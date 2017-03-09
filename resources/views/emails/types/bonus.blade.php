@extends('emails.layouts.main')

@section('title')
    <h1>Olá <span>{{ $user->username }}</span>,</h1>
@endsection

@section('message')
    <p>O seu bónus foi ativado com sucesso.</p>
    <p>&nbsp;</p>
    <p class="center"><a href="{{ $url }}" class="btn">DETALHES DO BÓNUS</a></p>
    <p>&nbsp;</p>
    @include('emails.layouts.welcome')
@endsection