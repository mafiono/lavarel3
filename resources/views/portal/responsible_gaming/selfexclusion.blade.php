@extends('layouts.portal', ['mini' => true])

@section('content')

        @include('portal.profile.head', ['active' => 'JOGO RESPONSÁVEL'])

        @include('portal.responsible_gaming.head_responsible_gaming', ['active' => 'AUTO-EXCLUSÃO'])
            
            <div class="col-xs-7 lin-xs-11 fleft">
                <div class="autoexclusao_main box-form-user-info lin-xs-12">
                    <div class="title-form-registo brand-title brand-color aleft">
                        Auto-Exclusão
                    </div>
                    <div class="brand-descricao descricao-mbottom aleft">                                    
                        Lorem ipsum dolor sit amet, consectetur adipisicing elit. A ab adipisci autem commodi dicta dolorem dolorum eaque et, explicabo magni maiores nam odio perspiciatis provident, quam rerum vitae. Consequatur cumque est et eum eveniet iure magni natus omnis provident repellendus.
                    </div>

                    @include('portal.messages')

                    @if (is_null($selfExclusion) || ! $selfExclusion->exists())
                        <div id="summary" class="warning-color"></div>
                        {!! Form::open(array('route' => array('jogo-responsavel/autoexclusao'),'id' => 'saveForm', 'class' => 'col-xs-12')) !!}
                            <div class="col-xs-6 micro-mtop">
                                <label>Selecionar Auto Exclusão</label>
                                <select class="col-xs-12" name="self_exclusion_type" id="self_exclusion_type">
                                    @foreach ($selfExclusionTypes as $key => $exclusao)
                                        @if ('3months_period' === $key)
                                            <option value="{{$key}}" selected>{{$exclusao}}</option>
                                        @else
                                            <option value="{{$key}}">{{$exclusao}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-xs-6 micro-mtop hidden" id="content-days">
                                <label>Tempo</label>
                                <input class="col-xs-7 input-group-left" type="number" name="dias" id="dias"><div class="input-group-label-right "> Dias</div>

                                <span class="has-error error" style="display:none;"> </span>
                            </div>

                            <div class="col-xs-12 micro-mtop warning-color hidden" id="reflexion-msg">
                                <p>Note que ao optar por um Periodo de Refleção, este poderá ser revogado a seu pedido a qualquer momento. Se pretende solicitar um periodo com carater obrigatório deverá selecionar uma das restantes opções de Auto-Exclusão, com periodo minimo obrigatório de 3 meses.</p>
                            </div>
                            <div class="col-xs-6 mini-mtop">
                                <input type="submit" class="col-xs-6 brand-botao brand-link fright formSubmit" value="Guardar" />
                            </div>

                        {!! Form::close() !!}
                    @else
                        @if(isset($selfExclusion->end_date))
                            <?php Carbon\Carbon::setLocale('pt'); setlocale(LC_TIME, 'portuguese'); ?>
                            <p><b class="brand-color">O seu pedido de auto-exclusão encontra-se em vigor.</b></p>
                            <p>Em vigor até {{$selfExclusion->end_date->formatLocalized('%d %B %Y')}}.</p>
                        @else
                            <p><b class="brand-color">A sua conta encontra-se <span class="warning-color">INACTIVA</span> por motivos de auto-exclusão permanente.</b></p>
                        @endif

                        <p><a target="_blank" href="/termos_e_condicoes#help-customer">Help Customer</a></p>
                        @if (is_null($revocation) || ! $revocation->exists())
                            <p>
                                {!! Form::open(array('route' => array('jogo-responsavel/cancelar-autoexclusao'),'id' => 'revokeForm', 'class' => 'col-xs-8')) !!}
                                <div class="col-xs-7 mini-mtop">
                                    <input type="hidden" name="self_exclusion_id" value="{{$selfExclusion->id}}">
                                    <input type="submit" class="col-xs-6 brand-botao brand-link fright formSubmit" value="Pedir Revogação" />
                                </div>
                                {!! Form::close() !!}
                            </p>
                        @else
                            <p>
                                {!! Form::open(array('route' => array('jogo-responsavel/revogar-autoexclusao'),'id' => 'revokeForm', 'class' => 'col-xs-8')) !!}
                                <div class="col-xs-7 mini-mtop">
                                    <input type="hidden" name="user_revocation_id" value="{{$revocation->id}}">
                                    <input type="submit" class="col-xs-6 brand-botao brand-link fright formSubmit" value="Cancelar Revogação" />
                                </div>
                                {!! Form::close() !!}
                            </p>
                        @endif
                    @endif
                </div>
            </div>
            <div class="clear"></div>               

        @include('portal.profile.bottom')
                        
@stop

@section('scripts')

    {!! HTML::script(URL::asset('/assets/portal/js/jquery.validate.js')); !!}    
    {!! HTML::script(URL::asset('/assets/portal/js/jquery.validate-additional-methods.js')); !!}
    {!! HTML::script(URL::asset('/assets/portal/js/plugins/jquery-form/jquery.form.min.js')); !!}
    {!! HTML::script(URL::asset('/assets/portal/js/forms.js')); !!}
    {!! HTML::script(URL::asset('/assets/portal/js/plugins/rx.umd.min.js')); !!}
    {!! HTML::script(URL::asset('/assets/portal/js/responsible_gaming/self-exclusion.js')); !!}

@stop