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

                    @if (!$authUser->selfExclusion->count())

                        {!! Form::open(array('route' => array('jogo-responsavel/autoexclusao'),'id' => 'saveForm', 'class' => 'col-xs-8')) !!}
                            <div class="col-xs-7 micro-mtop">
                                <label>Selecionar Estado da Conta</label>
                                <select class="col-xs-12" name="status">
                                    @foreach ($statuses as $key => $estado)
                                        @if ($authUser->status->id == $key)
                                            <option value="{{$key}}" selected>{{$estado}}</option>
                                        @else
                                            <option value="{{$key}}">{{$estado}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-xs-7 micro-mtop">
                                <label>Motivo</label>
                                <select class="col-xs-12" name="self_exclusion_type">
                                    @foreach ($selfExclusionTypes as $key => $exclusao)
                                        @if ($authUser->estado == $key)
                                            <option value="{{$key}}" selected>{{$exclusao}}</option>
                                        @else
                                            <option value="{{$key}}">{{$exclusao}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-xs-7 mini-mtop">
                                <input type="submit" class="col-xs-6 brand-botao brand-link fright formSubmit" value="Guardar" />
                            </div>

                        {!! Form::close() !!}
                    @else
                        <p>Existe um pedido de auto-exclusão pendente.</p>
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

@stop