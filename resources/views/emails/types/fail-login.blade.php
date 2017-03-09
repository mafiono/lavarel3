@extends('emails.layouts.main')

@section('title')
    <h1>Olá <span>{{ $user->username }}</span>,</h1>
@endsection

@section('message')
    <p>Detetámos uma tentativa de acesso à sua conta Casino Portugal sem sucesso.</p>
    <p>&nbsp;</p>
    <p>Caso não tenho solicitado este levantamento contacte o nosso <a href="mailto:apoiocliente@casinoportugal.pt" class="link">apoio ao cliente</a>.</p>
    <p>&nbsp;</p>
    <p>Caso deseje definir uma nova palavra-passe aceda a este link:</p>
    <p>&nbsp;</p>
    <p class="center"><a href="{{ $url }}" class="btn">DEFINIR PALAVRA-PASSE</a></p>
    <p>&nbsp;</p>
    @include('emails.layouts.welcome')
@endsection