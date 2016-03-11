@extends('layouts.portal', ['mini' => true])

@section('content')

    <div class="col-xs-12 home-back">
        <div class="main-contend main-opacity standalone">
            <div class="main white-back">
                @include('portal.partials.pop_header_signup', ['text' => empty($selfExclusion) && empty($identity) ?
                 'Esta pronto para Jogar!': ''])

                <div class="form-registo">
                    <div class="col-xs-2 lin-xs-12 fleft">
                        <div class="lin-xs-12 banner-back box-form-registo">
                            <img src="/assets/portal/img/banners/banner_ronaldo.png" alt="Banner Registo">
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

                        @include('portal.sign_up.footer', ['step' => 4, 'play' => '/'])
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

