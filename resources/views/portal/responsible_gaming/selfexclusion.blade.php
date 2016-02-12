@extends('layouts.portal')


@section('content')

        @include('portal.profile.head', ['active' => 'JOGO RESPONSÁVEL'])

        @include('portal.responsible_gaming.head_responsible_gaming', ['active' => 'AUTO-EXCLUSÃO'])
            
            <div class="col-xs-7 lin-xs-11 fleft">
                <div class="autoexclusao_main box-form-user-info lin-xs-12">
                    <div class="title-form-registo brand-title brand-color aleft">
                        Auto-Exclusão
                    </div>
                    <div class="brand-descricao descricao-mbottom aleft">                                    
                        Texto texto texto texto texto texto texto texto texto texto texto texto texto texto texto texto texto texto texto texto texto texto texto texto texto texto texto texto texto texto texto texto texto texto texto texto texto texto texto texto texto texto texto texto texto texto texto texto texto texto texto texto texto texto texto texto texto texto texto texto texto texto texto texto texto texto texto texto texto texto texto texto texto texto texto texto texto texto texto texto texto texto texto texto texto texto texto texto texto texto texto texto texto texto texto texto texto texto texto texto texto texto texto texto texto texto texto texto texto texto texto texto texto
                    </div>

                    @include('portal.messages')

                    @if (is_null($selfExclusion) || ! $selfExclusion->exists())
                        <div id="summary" class="warning-color"></div>
                        {!! Form::open(array('route' => array('jogo-responsavel/autoexclusao'),'id' => 'saveForm', 'class' => 'col-xs-8')) !!}
                            <div class="col-xs-9 micro-mtop">
                                <label>Selecionar Auto Exclusão</label>
                                <select class="col-xs-12" name="self_exclusion_type" id="self_exclusion_type">
                                    @foreach ($selfExclusionTypes as $key => $exclusao)
                                        @if ($authUser->estado == $key)
                                            <option value="{{$key}}" selected>{{$exclusao}}</option>
                                        @else
                                            <option value="{{$key}}">{{$exclusao}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-xs-9 micro-mtop" id="content-days">
                                <label>Tempo</label>
                                <input class="col-xs-7 input-group-left" type="number" name="dias" id="dias" min="90" value="90"><div class="input-group-label-right "> Dias</div>

                                <span class="has-error error" style="display:none;"> </span>
                            </div>

                            <div class="col-xs-7 mini-mtop">
                                <input type="submit" class="col-xs-6 brand-botao brand-link fright formSubmit" value="Guardar" />
                            </div>

                        {!! Form::close() !!}
                    @else
                        <p>Existe um pedido de auto-exclusão.</p>
                        @if(isset($selfExclusion->end_date))
                            <?php Carbon\Carbon::setLocale('pt'); ?>
                           Que acaba daqui a {{$selfExclusion->end_date->diffForHumans(null, true)}}.
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