@extends('layouts.faqs')

@section('content')
    {!! $legalDoc->description or 'restricted' !!}
@stop