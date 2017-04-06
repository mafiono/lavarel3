@extends('emails.layouts.main')

@section('title')
    <h1>Olá <span>{{ $name }}</span>,</h1>
@endsection

@section('message')
    <p>Informamos que recebemos a sua reclamação e responderemos o mais breve possível.</p>
    <p>&nbsp;</p>
    <p>Para mais informações contacte o nosso <a href="mailto:apoiocliente@casinoportugal.pt" class="link">apoio ao cliente</a>.</p>
    <p>&nbsp;</p>
    @include('emails.layouts.welcome')
@endsection