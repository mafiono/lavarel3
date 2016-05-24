@extends('layouts.faqs')

@section('content')
    {!! $legalDoc->description or 'faq' !!}
@stop
