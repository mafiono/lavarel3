<?php
    if (is_null($selfExclusion) || ! $selfExclusion->exists()){
        $link = 'jogo-responsavel/autoexclusao';
        $formId = 'saveForm';
        $btn = 'Guardar';
    } else {
        if (is_null($revocation) || ! $revocation->exists()){
            $link = 'jogo-responsavel/cancelar-autoexclusao';
            $formId = 'revokeForm';
            $btn = 'Pedir Revogação';
        } else {
            $link = 'jogo-responsavel/revogar-autoexclusao';
            $btn = 'Cancelar Revogação';
        }
    }
?>
@extends('portal.profile.layout', [
    'active1' => 'jogo_responsavel',
    'middle' => 'portal.responsible_gaming.head_responsible_gaming',
    'active2' => 'auto-exclusao',
    'form' => array('route' => array($link),'id' => $formId),
    'btn' => $btn])

@section('sub-content')
            
<div class="col-xs-12 autoexclusao_main fleft">
    <div class="title-form-registo brand-title brand-color aleft">
        Auto-Exclusão
    </div>
    <div class="brand-descricao micro-mbottom aleft">
        Lorem ipsum dolor sit amet, consectetur. Consequatur cumque est et eum eveniet iure magni natus omnis provident repellendus.
    </div>

    @include('portal.messages')

    @if (is_null($selfExclusion) || ! $selfExclusion->exists())
        <div id="summary" class="warning-color"></div>
        <div class="row">

            <div class="col-xs-8 micro-mtop">
                <label>Selecionar Auto Exclusão</label>
                <select class="col-xs-12" name="self_exclusion_type" id="self_exclusion_type">
                    @foreach ($selfExclusionTypes as $key => $exclusao)
                        @if ('reflection_period' === $key)
                            <option value="{{$key}}" selected>{{$exclusao}}</option>
                        @else
                            <option value="{{$key}}">{{$exclusao}}</option>
                        @endif
                    @endforeach
                </select>
            </div>

            <div class="col-xs-8 micro-mtop hidden" id="content-days">
                <label>Tempo</label>

                <div class="input-group col-xs-6 ">
                    <input type="number" class="form-control" name="dias" id="dias"/>
                    <span class="input-group-addon"> Dias</span>
                </div>

                <span class="has-error error" style="display:none;"> </span>
            </div>

            <div class="col-xs-8 micro-mtop" id="type_motive">
                <label>Motivo</label>
                <?php
                    $motives = [
                        'a' => 'Não consigo parar de jogar.',
                        'other' => 'Outra'
                    ];
                ?>
                {!! Form::select('type_motive', $motives, '', ['class' => 'col-xs-12']) !!}

                <span class="has-error error" style="display:none;"> </span>
            </div>

            <div class="col-xs-8 micro-mtop" id="motive" style="display: none">
                <label>Motivo</label>
                <textarea class="col-xs-7" name="motive" id="motive"></textarea>

                <span class="has-error error" style="display:none;"> </span>
            </div>

            <div class="col-xs-12 micro-mtop warning-color hidden" id="reflexion-msg">
                <p>Note que ao optar por um Periodo de Refleção, este poderá ser revogado a seu pedido a qualquer momento. Se pretende solicitar um periodo com carater obrigatório deverá selecionar uma das restantes opções de Auto-Exclusão, com periodo minimo obrigatório de 3 meses.</p>
            </div>
        </div>
    @else
        @if(isset($selfExclusion->end_date))
            <?php Carbon\Carbon::setLocale('pt'); setlocale(LC_TIME, 'portuguese'); ?>
            <p><b class="brand-color">O seu pedido de auto-exclusão encontra-se em vigor.</b></p>

            <p>Em vigor até {!! $selfExclusion->end_date->formatLocalized('%d/%m/%Y') !!}.</p>
        @else
            <p><b class="brand-color">A sua conta encontra-se <span class="warning-color">INACTIVA</span> por motivos de auto-exclusão permanente.</b></p>
        @endif

        <p><a target="_blank" href="/termos_e_condicoes#help-customer">Help Customer</a></p>
        @if (is_null($revocation) || ! $revocation->exists())
            <p>
                <div class="col-xs-7 mini-mtop">
                    <input type="hidden" name="self_exclusion_id" value="{{$selfExclusion->id}}">
                </div>
            </p>
        @else
            <p>
                <div class="col-xs-7 mini-mtop">
                    <input type="hidden" name="user_revocation_id" value="{{$revocation->id}}">
                </div>
            </p>
        @endif
    @endif
</div>
<div class="clear"></div>
@stop

@section('scripts')

    {!! HTML::script(URL::asset('/assets/portal/js/jquery.validate.js')); !!}    
    {!! HTML::script(URL::asset('/assets/portal/js/jquery.validate-additional-methods.js')); !!}
    {!! HTML::script(URL::asset('/assets/portal/js/plugins/jquery-form/jquery.form.min.js')); !!}
    {!! HTML::script(URL::asset('/assets/portal/js/forms.js')); !!}
    {!! HTML::script(URL::asset('/assets/portal/js/plugins/rx.umd.min.js')); !!}
    {!! HTML::script(URL::asset('/assets/portal/js/responsible_gaming/self-exclusion.js')); !!}

@stop