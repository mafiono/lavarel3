@extends('portal.profile.layout', [
    'active1' => 'jogo_responsavel',
    'middle' => 'portal.responsible_gaming.head_responsible_gaming',
    'active2' => 'limites_apostas',
    'form' => array('route' => array('jogo-responsavel/limites/apostas'),'id' => 'saveForm'),
    'btn' => 'Guardar'])

@section('styles')
<style>
    .bet .limit-check {
        display: inline;
    }
    .bet .limit-check label {
        top: 10px;
        display: inline-block !important;
        padding: 0 !important;
    }
</style>
@endsection
@section('sub-content')

    <div class="col-xs-12 responsavel_main fleft">
        <div class="title-form-registo brand-title brand-color aleft">
            Limites de Apóstas
        </div>

        <div class="brand-descricao mini-mbottom aleft">
            <b>Por Favor, defina aqui os seus limites de apóstas</b>
        </div>
        <div class="brand-descricao descricao-mbottom aleft">
            Texto texto texto texto texto texto texto texto texto texto texto texto texto texto texto texto texto
        </div>

        @include('portal.responsible_gaming.input', [
            'label' => 'Limite Diário',
            'typeId' => 'daily',
            'key' => 'limit_betting_daily'
        ])

        @include('portal.responsible_gaming.input', [
            'label' => 'Limite Semanal',
            'typeId' => 'weekly',
            'key' => 'limit_betting_weekly'
        ])

        @include('portal.responsible_gaming.input', [
            'label' => 'Limite Mensal',
            'typeId' => 'monthly',
            'key' => 'limit_betting_monthly'
        ])

        <div class="clear"></div>
        @include('portal.messages')
    </div>

@stop

@section('scripts')

    {!! HTML::script(URL::asset('/assets/portal/js/jquery.validate.js')); !!}    
    {!! HTML::script(URL::asset('/assets/portal/js/jquery.validate-additional-methods.js')); !!}
    {!! HTML::script(URL::asset('/assets/portal/js/plugins/jquery-form/jquery.form.min.js')); !!}
    {!! HTML::script(URL::asset('/assets/portal/js/forms.js')); !!}
    {!! HTML::script(URL::asset('/assets/portal/js/responsible_gaming/limits.js')); !!}

@stop