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
    $obj = [
        'active1' => 'jogo_responsavel',
        'middle' => 'portal.responsible_gaming.head_responsible_gaming',
        'active2' => 'auto-exclusao',
        'form' => array('route' => array($link),'id' => $formId),
        'btn' => $btn
    ];
    if (!$canSelfExclude) {
        unset($obj['form']);
        unset($obj['btn']);
    }
?>
@extends('portal.profile.layout', $obj)

@section('sub-content')

    <div class="row">
        <div class="col-xs-12">
            <div class="title">
                Seja Responsável
            </div>
            <div class="texto">
                Se tem algum problema com a prática de jogos e apostas online, dificuldades em controlar o tempo ou o dinheiro
                gasto com o jogo, ou outro sinal de risco,
                saiba que pode pedir a autoexclusão nesta página ou através da minuta que se encontra disponível na área
                específica do sítio na internet do <a target="_blank"
                    href="http://www.srij.turismodeportugal.pt/pt/jogo-responsavel/autoexclusao-e-proibicao/">SRIJ -
                    Serviço de Regulação e Inspeção de Jogos</a>.
                <br><br>
                O Período de auto exclusão tem a duração mínima de três (3) meses ou, na falta dessa indicação, por tempo
                indeterminado. Sem prejuízo do periodo de duração mínima de três (3) meses, o jogador pode comunicar
                o termo da autoexclusão ou, tendo o mesmo sido fixado, a sua antecipação, os quais se tornam eficazes decorrido
                o prazo de um (1) mês sobre essa comunicação.
                <br><br>
                Poderá ainda optar por uma breve pausa de jogo (prazo de reflexão) por um período máximo de trinta (30) dias,
                ficando impedido de efetuar apostas durante o período indicado mas com a possibilidade de efectuar levantamentos
                de fundos da sua conta.
                <br><br>
                Findo o termo do período de autoexclusão ou do prazo de reflexão, a sua conta volta a ficar ativa.
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            @if (!$canSelfExclude)
                <div class="title">A sua conta ainda não foi validada.</div>
            @elseif (is_null($selfExclusion) || ! $selfExclusion->exists())
                <div class="title">O motivo da sua decisão (opcional)</div>
                <div id="motive_option">
                    <?php
                    $motives = [
                            'a' => 'O jogo é a minha principal fonte de entretenimento',
                            'b' => 'Já não vejo o jogo como forma de entretenimento',
                            'c' => 'Passo muito tempo a jogar',
                            'd' => 'Sugestão médica',
                            'other' => 'Outro'
                    ];
                    ?>
                    @foreach($motives as $key => $value)
                        <div class="choice">
                            {!! Form::radio('type_motive', $key, null, ['id' => 'choice_'.$key]) !!} <label for="{{'choice_'.$key}}">{{$value}}</label>
                            <div class="check"><div class="inside"></div></div>
                        </div>
                    @endforeach

                    <span class="has-error error" style="display:none;"> </span>
                </div>
                <div class="col-xs-12" id="motive" style="display: none">
                    @include('portal.partials.input-text-area', ['field' => 'motive', 'value' => '','required' => false])
                </div>

                <div class="title">Autoexclusão</div>

                <div id="self_exclusion_type">
                    @foreach ($selfExclusionTypes as $key => $exclusao)
                        <div class="choice">
                            @if('reflection_period' === $key)
                                {!! Form::radio('self_exclusion_type', $key, true, ['id' => 'self_'.$key]) !!} <label for="{{'self_'.$key}}">{{$exclusao}}
                                    <input type="number" name="rp_dias" id="rp_dias" /> dias</label>
                            @elseif('minimum_period' === $key)
                                {!! Form::radio('self_exclusion_type', $key, null, ['id' => 'self_'.$key]) !!} <label for="{{'self_'.$key}}">{{$exclusao}}
                                    <input type="number" name="se_dias" id="se_dias" disabled="disabled" /> dias</label>
                            @else
                                {!! Form::radio('self_exclusion_type', $key, null, ['id' => 'self_'.$key]) !!} <label for="{{'self_'.$key}}">{{$exclusao}}</label>
                            @endif
                            <div class="check"><div class="inside"></div></div>
                        </div>
                    @endforeach
                </div>
            @else
                <br>
                @if(isset($selfExclusion->end_date))
                    <?php Carbon\Carbon::setLocale('pt'); setlocale(LC_TIME, 'portuguese'); ?>
                    <p><b class="brand-color">O seu pedido de {{trans('self_exclusion.types.' . $selfExclusion->self_exclusion_type_id)}} encontra-se em vigor.</b></p>

                    <p>Em vigor até {!! $selfExclusion->end_date->formatLocalized('%d/%m/%Y') !!}.</p>
                @else
                    <p><b class="brand-color">A sua conta encontra-se <span class="warning-color">INACTIVA</span> por motivos de
                            auto-exclusão permanente.</b></p>
                @endif

                <p><a target="_blank" href="/info/ajuda">{{trans('self_exclusion.link.name')}}</a></p>
                @if (is_null($revocation) || ! $revocation->exists())
                    <input type="hidden" name="self_exclusion_id" value="{{$selfExclusion->id}}">
                @else
                    <input type="hidden" name="user_revocation_id" value="{{$revocation->id}}">
                @endif
            @endif
        </div>
    </div>
@stop