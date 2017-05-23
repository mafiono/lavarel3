@extends('emails.layouts.main')

@section('title')
    <h1>Olá <span>{{ $name }}</span>,</h1>
@endsection

@section('message')
    <p>Informamos que a sua conta se encontra suspensa. </p>
    <p>&nbsp;</p>
    <p>Motivo: {{$motive}}</p>
    <p>&nbsp;</p>
    <p>Para mais informações contacte o nosso <a href="mailto:apoiocliente@casinoportugal.pt" class="link">apoio ao cliente</a>.</p>
    <p>&nbsp;</p>
@endsection