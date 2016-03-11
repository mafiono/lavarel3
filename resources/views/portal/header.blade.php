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
                    <li class="{{strcmp(Route::getCurrentRoute()->getPath(),"aovivo")?"":"active"}}"><a href="/aovivo">AO-VIVO</a></li>
                    <li class="{{strcmp(Route::getCurrentRoute()->getPath(),"desportos")?"":"active"}}"><a href="/desportos">DESPORTOS</a></li>
                    <li class="{{strcmp(Route::getCurrentRoute()->getPath(),"casino")?"":"active"}}"><a href="/casino">CASINO</a></li>
                    <li><a href="#vegas">VEGAS</a></li>
                </ul>
                <div class="navbar-fright">
                    <ul class="nav navbar-nav">
                        <div class="board-menu-div board-menu">
                            <a class="btn btn-header optiontype">ENGLISH</a>
                            <div class="menu_header menu_lang animated fadeIn">
                                <div class="menu_triangle"></div>
                                <div class="menu_triangle_contend acenter">
                                    <a href="#" class="btn btn-menu brand-trans">PORTUGUÊS</a>
                                    <a href="#" class="btn btn-menu brand-trans">DEUTSCH</a>
                                    <a href="#" class="btn btn-menu brand-trans">FRANÇAIS</a>
                                    <div class="clear"></div>
                                </div>
                            </div>
                        </div>
                        <div class="board-menu-div">
                            <a class="optiontype">
                                <div class="brand-link board-menu-option">
                                    <i class="fa fa-question"></i>
                                </div>
                            </a>
                            <div class="menu_header menu_faq animated fadeIn">
                                <div class="menu_triangle"></div>
                                <div class="menu_triangle_contend acenter">
                                    <a href="/definicoes" class="btn btn-menu brand-trans">DEFINIR OPÇÕES</a>
                                    <div class="clear"></div>
                                </div>
                            </div>
                        </div>
                        <div class="board-menu-div">
                            <a class="optiontype">
                                <div class="brand-link board-menu-option">
                                    <i class="fa fa-phone"></i>
                                </div>
                            </a>
                            <div class="menu_header menu_comunica animated fadeIn">
                                <div class="menu_triangle"></div>
                                <div class="menu_triangle_contend acenter">
                                    <a href="#" class="btn btn-menu brand-trans">CHAT</a>
                                    <a href="#" class="btn btn-menu brand-trans">MENSAGEM</a>
                                    <a href="#" class="btn btn-menu brand-trans">EMAIL</a>
                                    <a href="#" class="btn btn-menu brand-trans">SKYPE</a>
                                    <a href="#" class="btn btn-menu brand-trans">TELEFONE</a>
                                    <div class="clear"></div>
                                </div>
                            </div>
                        </div>
                        <div class="board-menu-div">
                            <a class="optiontype">
                                <div class="brand-link board-menu-option">
                                    <i class="fa fa-cog"></i>
                                </div>
                            </a>
                            <div class="menu_header menu_settings animated fadeIn">
                                <div class="menu_triangle"></div>
                                <div class="menu_triangle_contend acenter">
                                    <p class="col-xs-12 aleft brand-color"><b>Cotas</b></p>
                                    <ul class="col-xs-12 aleft">
                                        <li class="active">Decimal</li>
                                        <li>Fracional</li>
                                        <li>Americano</li>
                                        <li>HongKong</li>
                                        <li>Malay</li>
                                        <li>Indo</li>
                                    </ul>
                                    <div class="col-xs-12 fcenter separator-line"></div>
                                    <div class="col-xs-12 comunica_form_mini">
                                        <div class="neut-color fleft">
                                            Som
                                        </div>
                                        <div class="switch fright">
                                            <input id="som" class="cmn-toggle cmn-toggle-round" name="som" type="checkbox" checked="checked">
                                            <label for="som"></label>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                    <div class="col-xs-12 comunica_form_mini">
                                        <div class="neut-color fleft">
                                            Saldo
                                        </div>
                                        <div class="switch fright">
                                            <input id="Saldo" class="cmn-toggle cmn-toggle-round" name="Saldo" type="checkbox">
                                            <label for="Saldo"></label>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                    <div class="clear"></div>
                                </div>
                            </div>
                        </div>
                    </ul>
                </div>
            </div><!--/.nav-collapse -->
        </div>
    </nav>
    @endif
    <nav class="navbar navbar-default navbar-static-top navbar-2nd {{$showHeaderCss}}">
        <div class="col-xs-1">
            <a class="navbar-brand" rel="home" href="/" title="Bet Portugal">
                <img alt="ibetup" src="/assets/portal/img/favicon.png" />
            </a>
        </div>
        <div class="col-xs-5">
            <ul class="nav navbar-nav nav-onscroll">
                <li class="{{strcmp(Route::getCurrentRoute()->getPath(),"aovivo")?"":"active"}}"><a href="/aovivo">AO-VIVO</a></li>
                <li class="{{strcmp(Route::getCurrentRoute()->getPath(),"desportos")?"":"active"}}"><a href="/desportos">DESPORTOS</a></li>
                <li class="{{strcmp(Route::getCurrentRoute()->getPath(),"casino")?"":"active"}}"><a href="/casino">CASINO</a></li>
                <li><a href="#vegas">VEGAS</a></li>
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
                            <a href="/apostas" class="btn btn-menu brand-trans">MINHAS APOSTAS</a>
                            <a href="/comunicacao/mensagens" class="btn btn-menu brand-trans">MENSAGENS</a>
                            <a href="/logout" class="btn btn-menu brand-trans">SAIR</a>
                            <div class="clear"></div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        <div class="col-xs-4">
            @if(! $authUser)
                <button id="btnLogin" class="btn btn-brand btn-slim">Login</button>
                {!! Form::open(array('route' => array('login'),'id' => 'saveLoginForm')) !!}
                <div class="col-xs-4">
                    <input name="username" id="user-login" type="text" class="requred botao-registar brand-back" placeholder="username" />
                </div>
                <div class="col-xs-4">
                    <input name="password" id="pass-login" type="password" class="required botao-registar brand-back" placeholder="password" />
                </div>
                <div class="col-xs-4">
                    <input id="submit-login" type="submit" class="btn btn-brand btn-login-sub col-xs-6 formLoginSubmit" value="OK" />
                    <a href="/recuperar_password" class="btn btn-link col-xs-6">Recuperar dados</a>
                </div>
                {!! Form::close() !!}
            @else
                <div class="options">
                    <a class="optiontype btn btn-brand btn-slim">
                        <span class="balance">{{ $authUser->balance->balance_available }}</span> EUR
                    </a>
                    <div class="menu_header menu_account animated fadeIn clear">
                        <div class="menu_triangle"></div>
                        <div class="menu_triangle_contend acenter">
                            <div class="links col-xs-6">
                                <a href="/perfil" class="btn btn-menu brand-trans">PERFIL</a>
                                <a href="/promocoes" class="btn btn-menu brand-trans">PROMOÇÕES</a>
                                <a href="/banco/depositar" class="btn btn-menu brand-trans">DEPOSITAR</a>
                                <a href="/banco/levantar" class="btn btn-menu brand-trans">LEVANTAR</a>
                            </div>
                            <div class="saldos col-xs-6">
                                <div class="col-xs-12 brand-title brand-color aleft">
                                    Saldo Disponível
                                </div>
                                <div class="brand-descricao bborder neut-border mini-bpadding mini-mbottom available aleft">
                                    {{ $authUser->balance->balance_available }} EUR
                                </div>
                                <div class="col-xs-12 brand-title brand-color aleft">
                                    Contabilistico
                                </div>
                                <div class="brand-descricao bborder neut-border mini-bpadding mini-mbottom aleft">
                                    {{ $authUser->balance->balance_accounting }} EUR
                                </div>
                                <div class="col-xs-12 brand-title brand-color aleft">
                                    Bónus
                                </div>
                                <div class="brand-descricao bborder neut-border mini-bpadding mini-mbottom aleft">
                                    {{ $authUser->balance->balance_bonus }} EUR
                                </div>
                                <div class="col-xs-12 brand-title brand-color aleft">
                                    Total
                                </div>
                                <div class="brand-descricao mini-bpadding mini-mbottom total aleft">
                                    {{ $authUser->balance->balance_total }}  EUR
                                </div>
                            </div>
                            <div class="clear"></div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        <div class="col-xs-2 nav-ontop">
            <a href="#" class="btn btn-clean fright"><i class="fa fa-search"></i></a>
            <a href="#" class="btn btn-clean fright"><i class="fa fa-star"></i></a>
        </div>
        <div class="col-xs-2 nav-onscroll">
            <ul class="nav navbar-nav navbar-right">
                <div class="board-menu-div board-menu">
                    <a class="btn btn-header optiontype">ENGLISH</a>
                    <div class="menu_header menu_lang animated fadeIn">
                        <div class="menu_triangle"></div>
                        <div class="menu_triangle_contend acenter">
                            <a href="#" class="btn btn-menu brand-trans">PORTUGUÊS</a>
                            <a href="#" class="btn btn-menu brand-trans">DEUTSCH</a>
                            <a href="#" class="btn btn-menu brand-trans">FRANÇAIS</a>
                            <div class="clear"></div>
                        </div>
                    </div>
                </div>
                <div class="board-menu-div">
                    <a class="optiontype">
                        <div class="brand-link board-menu-option">
                            <i class="fa fa-question"></i>
                        </div>
                    </a>
                    <div class="menu_header menu_faq animated fadeIn">
                        <div class="menu_triangle"></div>
                        <div class="menu_triangle_contend acenter">
                            <a href="/definicoes" class="btn btn-menu brand-trans">DEFINIR OPÇÕES</a>
                            <div class="clear"></div>
                        </div>
                    </div>
                </div>
                <div class="board-menu-div">
                    <a class="optiontype">
                        <div class="brand-link board-menu-option">
                            <i class="fa fa-phone"></i>
                        </div>
                    </a>
                    <div class="menu_header menu_comunica animated fadeIn">
                        <div class="menu_triangle"></div>
                        <div class="menu_triangle_contend acenter">
                            <a href="#" class="btn btn-menu brand-trans">CHAT</a>
                            <a href="#" class="btn btn-menu brand-trans">MENSAGEM</a>
                            <a href="#" class="btn btn-menu brand-trans">EMAIL</a>
                            <a href="#" class="btn btn-menu brand-trans">SKYPE</a>
                            <a href="#" class="btn btn-menu brand-trans">TELEFONE</a>
                            <div class="clear"></div>
                        </div>
                    </div>
                </div>
                <div class="board-menu-div">
                    <a class="optiontype">
                        <div class="brand-link board-menu-option">
                            <i class="fa fa-cog"></i>
                        </div>
                    </a>
                    <div class="menu_header menu_settings animated fadeIn">
                        <div class="menu_triangle"></div>
                        <div class="menu_triangle_contend acenter">
                            <p class="col-xs-12 aleft brand-color"><b>Cotas</b></p>
                            <ul class="col-xs-12 aleft">
                                <li class="active">Decimal</li>
                                <li>Fracional</li>
                                <li>Americano</li>
                                <li>HongKong</li>
                                <li>Malay</li>
                                <li>Indo</li>
                            </ul>
                            <div class="col-xs-12 fcenter separator-line"></div>
                            <div class="col-xs-12 comunica_form_mini">
                                <div class="neut-color fleft">
                                    Som
                                </div>
                                <div class="switch fright">
                                    <input id="som" class="cmn-toggle cmn-toggle-round" name="som" type="checkbox" checked="checked">
                                    <label for="som"></label>
                                </div>
                                <div class="clear"></div>
                            </div>
                            <div class="col-xs-12 comunica_form_mini">
                                <div class="neut-color fleft">
                                    Saldo
                                </div>
                                <div class="switch fright">
                                    <input id="Saldo" class="cmn-toggle cmn-toggle-round" name="Saldo" type="checkbox">
                                    <label for="Saldo"></label>
                                </div>
                                <div class="clear"></div>
                            </div>
                            <div class="clear"></div>
                        </div>
                    </div>
                </div>
            </ul>
        </div>
    </nav>
</div>
<div>
    <div id="favorites-container" class="favorites-container hidden"></div>
</div>
