@extends('layouts.portal', ['mini' => true])

@section('content')
    <!---- CONTENT ---->
    <div class="col-xs-12 home-back">
        <div class="main-contend main-opacity standalone">
            <div class="main white-back perfil bs-wp">
                @include('portal.partials.pop_header', ['text' => 'Id jogador: '.$authUser->internalId()])
                @if (isset($form))
                    {!! Form::open($form) !!}
                @endif
                <div class="form-registo grid">
                    <div class="col-xs-5 no-padding">
                        <div class="">
                            <div class="col-xs-6 dash-right">
                                @include('portal.profile.head', ['active' => $active1])
                            </div>
                            <div class="col-xs-6 dash-right">
                                <?php if (! isset($input)) { $input = null; } ?>
                                @include($middle, ['active' => $active2, 'input' => $input])
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-7 no-padding">
                        <div class="sub-content">
                            @yield('sub-content')
                        </div>
                    </div>
                </div>
                <div class="row grid">
                    <div class="col-xs-offset-5 col-xs-7">
                        <div class="form-footer-rodape">
                            <div class="col-xs-6 form-marcadores acenter fleft">
                            </div>
                            <div class="col-xs-6 form-submit fleft">
                                <div class="btns aright" style="height: 30px">
                                    @if (isset($form))
                                        <input type="submit" class="col-xs-8 brand-botao brand-link formSubmit fright" value="{{$btn or 'Concluir'}}" />
                                    @endif
                                </div>
                            </div>
                            <div class="clear"></div>
                        </div>
                    </div>
                </div>
                @if (isset($form))
                    {!! Form::close() !!}
                @endif
            </div>
            <div class="clear"></div>
        </div>
    </div>
<!---- FIM CONTENT ---->
@stop

@section('scripts')

    {!! HTML::script(URL::asset('/assets/portal/js/jquery.validate.js')); !!}    
    {!! HTML::script(URL::asset('/assets/portal/js/jquery.validate-additional-methods.js')); !!}
    {!! HTML::script(URL::asset('/assets/portal/js/plugins/jquery-form/jquery.form.min.js')); !!}
    {!! HTML::script(URL::asset('/assets/portal/js/forms.js')); !!}
    {!! HTML::script(URL::asset('/assets/portal/js/plugins/rx.umd.min.js')); !!}
    {!! HTML::script(URL::asset('/assets/portal/js/perfil/personal_info.js')); !!}

@stop