@extends('emails.layouts.main')

@section('title')
    <h1>Olá <span>{{ $name }}</span>,</h1>
@stop

@section('message')
    <p>Informamos que a sua conta se encontra inativa há 120 dias. Pode voltar a utilizar a sua conta através:</p>
    <p>&nbsp;</p>
    <p class="center"><a href="{{ $url }}" class="btn">COMECE A APOSTAR</a></p>
    <p>&nbsp;</p>
    <p>Conforme o ponto 8.3. dos nossos <a href="{{$host}}/info/termos_e_condicoes">Termos e Condiçoes</a> .
        iremos proceder à cobrança de uma Taxa Administrativa mensal correspondente a 5% do saldo existente na
        conta de utilizador. </p>
    <p>&nbsp;</p>
    <p>Mantenha-se a par de todas as nossas promoções!</p>
    <p>&nbsp;</p>
    <p class="center"><a href="{{ $url }}" class="btn">PROMOÇÕES</a></p>
    <p>&nbsp;</p>
@stop