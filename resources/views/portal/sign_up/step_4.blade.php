@extends('layouts.portal', ['mini' => true])

@section('content')

    <div class="col-xs-12 home-back">
        <div class="main-contend main-opacity standalone">
            <div class="main white-back regist bs-wp">
                @include('portal.partials.pop_header_signup', ['text' => empty($selfExclusion) && empty($identity) ?
                 'Esta pronto para Jogar!': ''])

                <div class="form-registo bs-wp">
                    <div class="row">
                        <div class="col-xs-2">
                            <div class="banner-back">
                                <img src="/assets/portal/img/banners/banner_postiga.png" alt="Banner Registo">
                            </div>
                        </div>
                        <div class="col-xs-10 grid">
                            <div class="row">
                                <div class="box-form-registo">
                                    @if (empty($selfExclusion) && empty($identity))
                                        <div class="title-form-confirma brand-title brand-color acenter">
                                            A sua conta foi criada com sucesso!
                                        </div>

                                        <div class="col-xs-12 brand-descricao media-mbottom acenter">
                                            Foi enviado um email para a sua conta de correio, por favor valide esse email para poder começar a jogar!
                                        </div>

                                        <div class="col-xs-offset-2 col-xs-9">
                                            @include('portal.bank.deposit_partial')
                                        </div>

                                        <div class="media-mbottom"></div>

                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    {!! Form::open(array('route' => array('/'),'id' => 'saveForm')) !!}
                    <div class="row">
                        <div class="col-xs-offset-2 col-xs-10 grid">
                            @include('portal.sign_up.footer', ['step' => 4, 'final' => true])
                        </div>
                    </div>
                    <div class="clear"></div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>

@stop


@section('scripts')

    {!! HTML::script(URL::asset('/assets/portal/js/jquery.validate.js')); !!}
    {!! HTML::script(URL::asset('/assets/portal/js/jquery.validate-additional-methods.js')); !!}
    {!! HTML::script(URL::asset('/assets/portal/js/plugins/jquery-form/jquery.form.min.js')); !!}
    {!! HTML::script(URL::asset('/assets/portal/js/forms.js')); !!}
    {!! HTML::script(URL::asset('/assets/portal/js/plugins/rx.umd.min.js')) !!}

    {!! HTML::script(URL::asset('/assets/portal/js/bank/deposit.js')) !!}

@stop

