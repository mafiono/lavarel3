<?php
    $formId = 'saveForm';
    if (is_null($selfExclusion) || ! $selfExclusion->exists()){
        $link = 'jogo-responsavel/autoexclusao';
        $btn = 'Guardar';
    } else {
        if (is_null($revocation) || ! $revocation->exists()){
            $link = 'jogo-responsavel/cancelar-autoexclusao';
            $formId = 'revokeForm';
            $btn = 'Pedir Revogação';
        } else {
            $link = 'jogo-responsavel/revogar-autoexclusao';
            $btn = 'Cancelar';
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
            
<div class="title">
        Seja Responsável
    </div>
    <div class="texto">
      Se tem algum problema com a prática de jogos e apostas online, dificuldades em controlar o tempo ou o dinheiro gasto com o jogo, ou outro sinal de risco,
        saiba que pode pedir a autoexclusão nesta página ou através da minuta que se encontra disponível na área específica do sítio na internet do <a target="_blank" href="http://www.srij.turismodeportugal.pt/pt/">SRIJ - Serviço de Regulação e Inspeção de Jogos</a>.
        <br><br><br>
        O Período de auto exclusão tem a duração mínima de três (3) meses ou, na falta dessa indicação, por tempo indeterminado. Sem prejuízo do periodo de duração mínima de três (3) meses, o jogador pode comunicar
        o termo da autoexclusão ou, tendo o mesmo sido fixado, a sua antecipação, os quais se tornam eficazes decorrido o prazo de um (1) mês sobre essa comunicação.
        <br><br><br>
        Poderá ainda optar por uma breve pausa de jogo (prazo de reflexão) por um período máximo de trinta (30) dias, ficando impedido de efetuar apostas durante o período indicado mas com a possibilidade de efectuar levantamentos de fundos da sua conta.
        <br><br><br>
        Findo o termo do período de autoexclusão ou do prazo de reflexão, a sua conta volta a ficar ativa.
    </div>

    @include('portal.messages')
    
    @if (!$canSelfExclude)
      <div class="title">A sua conta ainda não foi validada.</div>
    @elseif (is_null($selfExclusion) || ! $selfExclusion->exists())

        <div class="title" id="type_motive">
            O motivo da sua decisão
            <?php
            $motives = [
                    'a' => 'O jogo é a minha principal fonte de entretenimento',
                    'b' => 'Já não vejo o jogo como forma de entretenimento.',
                    'c' => 'Passo muito tempo a jogar.',
                    'd' => 'Sugestão médica.',
                    'other' => 'Outra'
            ];
            ?>
            {!! Form::select('type_motive', $motives, '',['class'=>'grande']) !!}

            <span class="has-error error" style="display:none;"> </span>
        </div>

        <div class="col-xs-8 micro-mtop" id="motive" style="display: none">

            <textarea  name="motive" id="motive"> Motivo </textarea>

            <span class="has-error error" style="display:none;"> </span>
        </div>


                <div class="title">Autoexclusão</div>

                <select name="self_exclusion_type" id="self_exclusion_type">
                    @foreach ($selfExclusionTypes as $key => $exclusao)
                        @if ('reflection_period' === $key)
                            <option value="{{$key}}" selected>{{$exclusao}}</option>
                        @else
                            <option value="{{$key}}">{{$exclusao}}</option>
                        @endif
                    @endforeach
                </select>


            <div id="content-days">

                    <input type="number"  name="dias" id="dias"/>
                    <span class="input-group-addon"> Dias</span>

                <span class="has-error error" style="display:none;"> </span>
            </div>


    @else
        @if(isset($selfExclusion->end_date))
            <?php Carbon\Carbon::setLocale('pt'); setlocale(LC_TIME, 'portuguese'); ?>
            <p><b class="brand-color">O seu pedido de auto-exclusão encontra-se em vigor.</b></p>

            <p>Em vigor até {!! $selfExclusion->end_date->formatLocalized('%d/%m/%Y') !!}.</p>
        @else
            <p><b class="brand-color">A sua conta encontra-se <span class="warning-color">INACTIVA</span> por motivos de auto-exclusão permanente.</b></p>
        @endif

        <p><a target="_blank" href="/info/ajuda">Help Customer</a></p>
        @if (is_null($revocation) || ! $revocation->exists())
            <p>
                <div class="col-xs-7 mini-mtop">
                    <input type="hidden" name="self_exclusion_id" value="{{$selfExclusion->id}}">
                </div>

        @else

                <div class="col-xs-7 mini-mtop">
                    <input type="hidden" name="user_revocation_id" value="{{$revocation->id}}">
                </div>

        @endif
    @endif

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