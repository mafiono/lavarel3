@extends('layouts.faqs')

@section('content')
    {!! $legalDoc->description or 'help' !!}
@stop
