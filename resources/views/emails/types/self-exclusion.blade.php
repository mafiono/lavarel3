@extends('emails.layouts.main')

@section('title')
    <h1>Olá <span>{{ $user->username }}</span>,</h1>
@endsection

@section('message')
    @if ($exclusion === 'reflection_period')
        <p>Informamos que o seu pedido de Reflexão por {{$time}} dias foi efetuado com sucesso.</p>
    @elseif($exclusion === 'undetermined_period')
        <p>Informamos que o seu pedido de autoexclusão por tempo indeterminado foi efetuado com sucesso.</p>
    @else
        <p>Informamos que o seu pedido de autoexclusão por {{$time}} meses foi efetuado com sucesso.</p>
    @endif
    <p>&nbsp;</p>
    @include('emails.layouts.welcome', ['luck' => false ])
@endsection