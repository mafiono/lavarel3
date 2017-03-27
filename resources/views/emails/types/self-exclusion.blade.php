@extends('emails.layouts.main')

@section('title')
    <h1>Olá <span>{{ $name }}</span>,</h1>
@endsection

@section('message')
    @if ($exclusion === 'reflection_period')
        <p>Informamos que o seu pedido de Reflexão por {{$time}} dias foi efetuado com sucesso.</p>
    @elseif($exclusion === 'undetermined_period')
        <p>Informamos que o seu pedido de autoexclusão por tempo indeterminado foi efetuado com sucesso.</p>
    @elseif($exclusion === '3months_period')
        <p>Informamos que o seu pedido de autoexclusão por 3 meses foi efetuado com sucesso.</p>
    @elseif($exclusion === '1year_period')
        <p>Informamos que o seu pedido de autoexclusão por 12 meses foi efetuado com sucesso.</p>
    @else
        <p>Informamos que o seu pedido de autoexclusão por {{$time}} meses foi efetuado com sucesso.</p>
    @endif
    <p>&nbsp;</p>
    @include('emails.layouts.welcome', ['luck' => false ])
@endsection