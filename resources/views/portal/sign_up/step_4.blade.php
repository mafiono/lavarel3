@extends('layouts.portal')


@section('content')

    <div class="col-xs-12 home-back">
        <div class="main-contend main-opacity">
            <div class="main white-back">
                <div class="brand-back brand-box-title">
                    <div class="col-xs-2 main-logo fleft">
                        <img alt="ibetup" src="/assets/portal/img/main_logo.png" />
                    </div>
                    <div class="col-xs-10 brand-title aright white-color fleft">
                        @if (empty($selfExclusion) && empty($identity))
                            Está pronto para jogar!
                        @endif
                    </div>
                    <div class="clear"></div>
                </div>
                
                <div class="form-registo">
                    <div class="col-xs-2 lin-xs-12 fleft">
                        <div class="lin-xs-12 brand-back box-form-registo">
                            Banner Registo
                        </div>
                    </div>
                    <div class="col-xs-10 lin-xs-9 fleft">
                        <div class="col-xs-12 fleft">
                            <div class="box-form-registo">
                                @if (empty($selfExclusion) && empty($identity))
                                    <div class="title-form-confirma brand-title brand-color acenter">
                                        A sua conta foi criada com sucesso!
                                    </div>

                                    <div class="col-xs-12 brand-descricao media-mbottom acenter">
                                        Foi enviado um email para a sua conta de correio, por favor valide esse email para poder começar a jogar!
                                    </div>

                                    @include('portal.bank.deposit_partial')

                                    <div class="media-mbottom"></div>

                                @endif
                            </div>
                        </div>
                        <div class="clear"></div>

                        <div class="form-rodape">
                            @if (empty($selfExclusion) && empty($identity))
                                <div class="col-xs-12 form-marcadores acenter fright">
                                    <p>1</p>
                                    <p>2</p>
                                    <p>3</p>
                                    <p class="brand-botao">4</p>
                                </div>
                            @endif
                            <div class="clear"></div>
                        </div>
                    </div>
                    <div class="clear"></div>
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

