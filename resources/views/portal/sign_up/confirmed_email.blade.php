@extends('layouts.portal', ['mini' => true])

@section('content')

    <div class="col-xs-12 home-back">
        <div class="main-contend main-opacity standalone">
            <div class="main white-back">
                @include('portal.partials.pop_header_signup', ['text' => 'Obrigado!'])

                <div class="form-registo">
                    <div class="col-xs-2 lin-xs-12 fleft">
                        <div class="lin-xs-12 banner-back box-form-registo">
                            <img src="/assets/portal/img/banners/banner_ronaldo.png" alt="Banner Registo">
                        </div>
                    </div>
                    <div class="col-xs-10 lin-xs-9 fleft">
                        <div class="col-xs-12 fleft">
                            <div class="box-form-registo">
                                <div class="title-form-confirma brand-title brand-color acenter">
                                    Obrigado por confirmar a sua Conta!
                                </div>

                                <div class="confirma-spinner acenter" style="height: 99px;">

                                </div>

                                <div class="col-xs-7 brand-descricao acenter fcenter">
                                    Clique <a href="/" >Aqui</a> para começar a jogar!
                                </div>
                            </div>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="clear"></div>
                </div>
            </div>
        </div>
    </div>

@stop