@extends('emails.layouts.main')

@section('title')
    <h1>Olá <span>{{ $name }}</span>,</h1>
@stop

@section('message')
    <p>Tem mensagens novas por ler na sua conta Casino Portugal.</p>
    <p>&nbsp;</p>
    <p class="center"><a href="{{ $url }}" class="btn">VER MENSAGEM</a></p>
    <p>&nbsp;</p>
    @include('emails.layouts.welcome')
@stop