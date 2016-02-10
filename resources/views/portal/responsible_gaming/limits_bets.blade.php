@extends('layouts.portal')

@section('styles')
<style>
    .limit-check {
        display: inline;
    }
    .limit-check label {
        top: 10px;
        display: inline-block !important;
        padding: 0 !important;
    }
</style>
@endsection
@section('content')

        @include('portal.profile.head', ['active' => 'JOGO RESPONSÁVEL'])

        @include('portal.responsible_gaming.head_responsible_gaming', ['active' => 'LIMITES DE APÓSTAS'])

        {!! Form::open(array('route' => array('jogo-responsavel/limites/apostas'),'id' => 'saveForm')) !!}
        <?php
            /* @var $limites \App\UserLimit */
            $limites = $authUser->limits ?: new \App\UserLimit;
        ?>
        <div class="col-xs-5 lin-xs-11 fleft">
            <div class="responsavel_main box-form-user-info lin-xs-12">
                <div class="title-form-registo brand-title brand-color aleft">
                    Limites de Apóstas
                </div>

                <div class="brand-descricao mini-mbottom aleft">
                    <b>Por Favor, defina aqui os seus limites de apóstas</b>
                </div>
                <div class="brand-descricao descricao-mbottom aleft">
                    Texto texto texto texto texto texto texto texto texto texto texto texto texto texto texto texto texto
                </div>

                @include('portal.messages')

                @include('portal.responsible_gaming.input', [
                    'label' => 'Limite Diário',
                    'typeId' => 'daily',
                    'value' => $limites->limit_betting_daily
                ])

                @include('portal.responsible_gaming.input', [
                    'label' => 'Limite Semanal',
                    'typeId' => 'weekly',
                    'value' => $limites->limit_betting_weekly
                ])

                @include('portal.responsible_gaming.input', [
                    'label' => 'Limite Mensal',
                    'typeId' => 'monthly',
                    'value' => $limites->limit_betting_monthly
                ])

                <div class="col-xs-8 mini-mtop">
                    <input type="submit" class="col-xs-6 brand-botao brand-link fright formSubmit" value="Guardar" />
                </div>
            </div>
        </div>
        <div class="clear"></div>
        {!! Form::close() !!}

        @include('portal.profile.bottom')
                        
@stop

@section('scripts')

    {!! HTML::script(URL::asset('/assets/portal/js/jquery.validate.js')); !!}    
    {!! HTML::script(URL::asset('/assets/portal/js/jquery.validate-additional-methods.js')); !!}
    {!! HTML::script(URL::asset('/assets/portal/js/plugins/jquery-form/jquery.form.min.js')); !!}
    {!! HTML::script(URL::asset('/assets/portal/js/forms.js')); !!}
    {!! HTML::script(URL::asset('/assets/portal/js/responsible_gaming/limits.js')); !!}

@stop