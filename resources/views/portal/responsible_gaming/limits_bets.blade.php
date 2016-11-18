@extends('portal.profile.layout', [
    'active1' => 'jogo_responsavel',
    'middle' => 'portal.responsible_gaming.head_responsible_gaming',
    'active2' => 'limites_apostas'

    ])


@section('sub-content')

    <div class="center">
        <div class="title">Limites de Depósito(EUR)</div>



            <div class="texto">Texto texto textoTexto texto textoTexto texto textoTexto texto textoTexto texto textoTexto texto texto</div>
        {!! Form::open(array('route' => array('jogo-responsavel/limites'),'id' => 'saveForm')) !!}


        @include('portal.responsible_gaming.input', [
            'label' => 'Limite Diário',
            'typeId' => 'dailydeposit',
            'key' => 'limit_deposit_daily'
        ])

        @include('portal.responsible_gaming.input', [
            'label' => 'Limite Semanal',
            'typeId' => 'weeklydeposit',
            'key' => 'limit_deposit_weekly'
        ])

        @include('portal.responsible_gaming.input', [
            'label' => 'Limite Mensal',
            'typeId' => 'monthlydeposit',
            'key' => 'limit_deposit_monthly'
        ])
        <input type="submit" value="Definir">
        {!! Form::close() !!}
            </div>
    <div class="center">
        <div style="margin-top:20px;">
            <div class="title">Limites de Apostas(EUR)</div>
        <div class="texto">Texto texto textoTexto texto textoTexto texto textoTexto texto textoTexto texto textoTexto texto texto</div>


        {!! Form::open(array('route' => array('jogo-responsavel/limites/apostas'),'id' => 'saveForm')) !!}

        @include('portal.responsible_gaming.input', [
           'label' => 'Limite Diário',
           'typeId' => 'dailybet',
           'key' => 'limit_betting_daily'
       ])

        @include('portal.responsible_gaming.input', [
            'label' => 'Limite Semanal',
            'typeId' => 'weeklybet',
            'key' => 'limit_betting_weekly'
        ])

        @include('portal.responsible_gaming.input', [
            'label' => 'Limite Mensal',
            'typeId' => 'monthlybet',
            'key' => 'limit_betting_monthly'
        ])
        <input type="submit" value="Definir">
        {!! Form::close() !!}

            </div>
    </div>



@stop

@section('scripts')

    {!! HTML::script(URL::asset('/assets/portal/js/jquery.validate.js')); !!}    
    {!! HTML::script(URL::asset('/assets/portal/js/jquery.validate-additional-methods.js')); !!}
    {!! HTML::script(URL::asset('/assets/portal/js/plugins/jquery-form/jquery.form.min.js')); !!}
    {!! HTML::script(URL::asset('/assets/portal/js/forms.js')); !!}
    {!! HTML::script(URL::asset('/assets/portal/js/responsible_gaming/limits.js')); !!}

@stop