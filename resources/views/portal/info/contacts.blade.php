@extends('layouts.faqs')

@section('content')
    {!! $legalDoc->description or 'contacts' !!}

    <div class="row">
        <div style="margin-left: 30%">
            <a href="mailto:info@sfponline.pt" class="btn btn-default" target="_blank"><i class="fa fa-mail"></i> Email<br>info@sfponline.pt</a>
        </div>
    </div>
@stop
