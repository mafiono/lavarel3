@extends('emails.layouts.main')

@section('title')
    <h1>Olá <span>{{ $name }}</span>,</h1>
@endsection

@section('message')
    <p>Informamos que o seu documento de identidade expirará em breve.</p>
    <p>&nbsp;</p>
    <p>Pode submeter um novo comprovativo na área de perfil da sua conta Casino Portugal.</p>
    <p>&nbsp;</p>
    <p class="center"><a href="{{ $url }}" class="btn">ENVIAR COMPROVATIVO</a></p>
    <p>&nbsp;</p>
    @include('emails.layouts.welcome', ['luck' => false])
@endsection