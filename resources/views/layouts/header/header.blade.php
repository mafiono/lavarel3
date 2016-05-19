<?php
    $showHeader = ! isset($mini);
    $showHeaderCss = $showHeader ? "" : "standalone";
?>
<!---- TOP BAR ---->
<div class="bs-wp">
    @if ($showHeader)
    <nav class="navbar navbar-default navbar-static-top">
        <div class="">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle navbar-toggle-left collapsed" data-toggle="collapse"
                        data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" rel="home" href="/" title="Bet Portugal">
                    <img alt="ibetup" src="/assets/portal/img/main_logo.png" />
                </a>
            </div>
            <div id="navbar" class="navbar-collapse collapse">
                <ul class="nav navbar-nav">
                    @include('layouts.header.menu')
                </ul>
                <div class="navbar-fright">
                    <ul class="nav navbar-nav">
                        @include('layouts.header.top_right')
                    </ul>
                </div>
            </div><!--/.nav-collapse -->
        </div>
    </nav>
    @endif
    <nav class="navbar navbar-default navbar-static-top navbar-2nd container-fluid {{$showHeaderCss}}">
        <div class="clearfix">
            <div class="col-xs-1">
                <a class="navbar-brand" rel="home" href="/" title="Bet Portugal">
                    <img alt="ibetup" src="/assets/portal/img/favicon.png" />
                </a>
            </div>
            <div class="col-xs-5">
                <ul class="nav navbar-nav nav-onscroll">
                    @include('layouts.header.menu')
                </ul>
                @if(! $authUser)
                    <a href="/registar" class="btn btn-brand btn-slim fright">Registar</a>
                @else
                    <div class="options fright">
                        <a class="optiontype btn btn-brand btn-slim fright">{{ $authUser->username }}</a>
                        <div class="menu_header menu_user animated fadeIn clear">
                            <div class="menu_triangle"></div>
                            <div class="menu_triangle_contend acenter">
                                <div class="col-xs-12 acenter">
                                    <p class="brand-color2"><b class="brand-color">ID</b>{{ Auth::user()->internalId() }}</p>
                                </div>
                                <a href="/perfil" class="btn btn-menu brand-trans">Perfil</a>
                                <a href="/historico" class="btn btn-menu brand-trans">Minhas apostas</a>
                                <a href="/comunicacao/mensagens" class="btn btn-menu brand-trans">Mensagens</a>
                                <a href="/logout" class="btn btn-menu brand-trans">Sair</a>
                                <div class="clear"></div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            <div class="col-xs-4" id="form-login">
                @if(! $authUser)
                    <button id="btnLogin" class="btn btn-brand btn-slim">Login</button>
                    {!! Form::open(array('route' => array('login'),'id' => 'saveLoginForm')) !!}
                    <div class="col-xs-4">
                        <input name="username" id="user-login" type="text" class="required botao-registar brand-back" placeholder="username" />
                    </div>
                    <div class="col-xs-4">
                        <input name="password" id="pass-login" type="password" class="required botao-registar brand-back" placeholder="password" />
                    </div>
                    <div class="col-xs-4">
                        <input id="submit-login" type="submit" class="btn btn-brand btn-login-sub col-xs-4 formLoginSubmit" value="OK" />
                        <a href="#" class="btn btn-link col-xs-6" id="btn_reset_pass">Recuperar dados</a>
                    </div>
                    {!! Form::close() !!}
                @else
                    <script>
                        $(function() {
                            setInterval(function() {
                                $.getJSON("{!! route('balance') !!}")
                                        .done(function (data) {
                                            $("#headerBalance").html(data.balance);
                                            $("#popupBalance").html(data.balance);
                                            $("#popupBonus").html(data.bonus);
                                            $("#popupBalanceTotal").html(data.total);
                                        });
                            }, 5000);
                        });
                    </script>
                    <div class="options">
                        <a class="optiontype btn btn-brand btn-slim">
                            <span id="headerBalance" class="balance">{{ number_format($authUser->balance->balance_available, 2, '.', ',') }}</span> EUR
                        </a>
                        <div class="menu_header menu_account animated fadeIn clear">
                            <div class="menu_triangle"></div>
                            <div class="menu_triangle_contend acenter">
                                <div class="links col-xs-6">
                                    <a href="/banco/depositar" class="btn btn-menu brand-trans">Depositar</a>
                                    <a href="/promocoes" class="btn btn-menu brand-trans">Promoções</a>
                                    <a href="/perfil" class="btn btn-menu brand-trans">Opções</a>
                                </div>
                                <div class="saldos col-xs-6">
                                    <div class="col-xs-12 brand-title brand-color aleft">
                                        Saldo Disponível
                                    </div>
                                    <div class="brand-descricao bborder neut-border mini-bpadding mini-mbottom available aleft">
                                        <span id="popupBalance">{{ number_format($authUser->balance->balance_available, 2, '.', ',') }}</span> EUR
                                    </div>
                                    {{--<div class="col-xs-12 brand-title brand-color aleft">--}}
                                        {{--Contabilistico--}}
                                    {{--</div>--}}
                                    {{--<div class="brand-descricao bborder neut-border mini-bpadding mini-mbottom aleft">--}}
                                        {{--{{ number_format($authUser->balance->balance_accounting, 2, '.', ',') }} EUR--}}
                                    {{--</div>--}}
                                    <div class="col-xs-12 brand-title brand-color aleft">
                                        Bónus
                                    </div>
                                    <div class="brand-descricao bborder neut-border mini-bpadding mini-mbottom aleft">
                                        <span id="popupBonus">{{ number_format($authUser->balance->balance_bonus, 2, '.', ',') }}</span> EUR
                                    </div>
                                    <div class="col-xs-12 brand-title brand-color aleft">
                                        Total
                                    </div>
                                    <div class="brand-descricao mini-bpadding mini-mbottom total aleft">
                                        <span id="popupBalanceTotal">{{ number_format($authUser->balance->balance_total, 2, '.', ',') }}</span>  EUR
                                    </div>
                                </div>
                                <div class="clear"></div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            <div class="col-xs-2 nav-ontop">
                <a href="#" class="btn btn-clean fright" id="btn-search"><i class="fa fa-search"></i></a>
                <input type="text" class="botao-registar brand-back" placeholder="Procurar">
                <a href="#" class="btn btn-clean fright"><i class="fa fa-star"></i></a>
            </div>
            <div class="col-xs-2 nav-onscroll">
                <ul class="nav navbar-nav navbar-right">
                    @include('layouts.header.top_right')
                </ul>
            </div>
        </div>
        @if(! $authUser)
            @include('layouts.header.reset_password')
        @endif
    </nav>
</div>
<div>
    <div id="favorites-container" class="favorites-container hidden"></div>
</div>
