@extends('layouts.faqs')

@section('content')
    {!! $legalDoc->description or 'contacts' !!}
@stop
