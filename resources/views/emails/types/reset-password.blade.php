@extends('emails.layouts.main')

@section('title')
    <h1>Olá <span>{{ $name }}</span>,</h1>
@stop

@section('message')
    <p>Para definir a sua nova palavra-passe de acesso à sua conta Casino Portugal, aceda a este link:</p>
    <p>&nbsp;</p>
    <p class="center"><a href="{{ $url }}" class="btn">DEFINIR PALAVRA-PASSE</a></p>
    <p>&nbsp;</p>
    @include('emails.layouts.welcome')
@stop