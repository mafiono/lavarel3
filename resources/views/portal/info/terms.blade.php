@extends('layouts.faqs')

@section('content')
    {!! $legalDoc->description or 'terms' !!}
@stop