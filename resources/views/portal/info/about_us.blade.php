@extends('layouts.faqs')

@section('content')
    {!! $legalDoc->description or 'about_us' !!}
@stop
