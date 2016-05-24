@extends('layouts.faqs')

@section('content')
    {!! $legalDoc->description or 'politica_cookies' !!}
@stop
