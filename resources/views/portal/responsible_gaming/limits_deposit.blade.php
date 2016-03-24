@extends('portal.profile.layout', [
    'active1' => 'jogo_responsavel',
    'middle' => 'portal.responsible_gaming.head_responsible_gaming',
    'active2' => 'limites_deposito'])

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
{!! Form::open(array('route' => array('jogo-responsavel/limites'),'id' => 'saveForm')) !!}
<div class="col-xs-12 lin-xs-11 fleft">
    <div class="responsavel_main box-form-user-info lin-xs-12">
        <div class="title-form-registo brand-title brand-color aleft">
            Limites de Depósito
        </div>

        <div class="brand-descricao mini-mbottom aleft">
            <b>Por Favor, defina aqui os seus limites de depósito</b>
        </div>
        <div class="brand-descricao descricao-mbottom aleft">
            Texto texto texto texto texto texto texto texto texto texto texto texto texto texto texto texto texto
        </div>

        @include('portal.messages')

        @include('portal.responsible_gaming.input', [
            'label' => 'Limite Diário',
            'typeId' => 'daily',
            'key' => 'limit_deposit_daily'
        ])

        @include('portal.responsible_gaming.input', [
            'label' => 'Limite Semanal',
            'typeId' => 'weekly',
            'key' => 'limit_deposit_weekly'
        ])

        @include('portal.responsible_gaming.input', [
            'label' => 'Limite Mensal',
            'typeId' => 'monthly',
            'key' => 'limit_deposit_monthly'
        ])

        <div class="col-xs-8 mini-mtop">
            <input type="submit" class="col-xs-6 brand-botao brand-link fright formSubmit" value="Guardar" />
        </div>
    </div>
</div>
<div class="clear"></div>
{!! Form::close() !!}
@stop

@section('scripts')

    {!! HTML::script(URL::asset('/assets/portal/js/jquery.validate.js')); !!}    
    {!! HTML::script(URL::asset('/assets/portal/js/jquery.validate-additional-methods.js')); !!}
    {!! HTML::script(URL::asset('/assets/portal/js/plugins/jquery-form/jquery.form.min.js')); !!}
    {!! HTML::script(URL::asset('/assets/portal/js/forms.js')); !!}
    {!! HTML::script(URL::asset('/assets/portal/js/responsible_gaming/limits.js')); !!}

@stop