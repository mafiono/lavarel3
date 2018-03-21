@extends('emails.layouts.main')

@section('title')
    <h1>Olá <span>{{ $name }}</span>,</h1>
@stop

@section('message')
    <p>Relembramos que ainda não confirmou o seu email de registo.</p>
    <p>&nbsp;</p>
    <p class="center"><a href="{{ $url }}" class="btn">CONFIRMAR</a></p>
    <p>&nbsp;</p>
    @include('emails.layouts.welcome')
@stop