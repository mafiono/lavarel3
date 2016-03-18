@extends('layouts.portal', ['mini' => true])

@section('content')

        @include('portal.profile.head', ['active' => 'COMUNICAÇÃO'])

        @include('portal.communications.head_communication', ['active' => 'MENSAGENS'])

        @include('portal.profile.bottom')
                        
@stop

@section('scripts')


@stop