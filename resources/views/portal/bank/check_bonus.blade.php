@extends('layouts.portal')


@section('content')

    @include('portal.profile.head', ['active' => 'BANCO'])

    @include('portal.bank.head_bank', ['active' => ''])


    @include('portal.profile.bottom')

@stop

@section('scripts')


@stop

