<!---- TOP BAR ---->
<div class="bs-wp">
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
                    <li class="active"><a href="/aovivo">AO-VIVO</a></li>
                    <li><a href="/desportos">DESPORTOS</a></li>
                    <li><a href="/casino">CASINO</a></li>
                    <li><a href="#vegas">VEGAS</a></li>
                </ul>
                <div class="navbar-fright">
                    <ul class="nav navbar-nav">
                        <div class="board-menu-div board-menu">
                            <div class="brand-link board-menu-option">
                                <select>
                                    <option value="English" selected="selected">English</option>
                                    <option value="Português">Português</option>
                                </select>
                            </div>
                        </div>
                        <div class="board-menu-div">
                            <a class="optiontype">
                                <div class="brand-link board-menu-option">
                                    <i class="fa fa-question"></i>
                                </div>
                            </a>
                            <div class="menu_header menu_comunica animated fadeIn">
                                <div class="menu_triangle fcenter"></div>
                                <div class="menu_triangle_contend acenter">
                                    <a href="/definicoes">
                                        <div class="col-xs-12 brand-botao-rev2 brand-trans">
                                            DEFINIR OPÇÕES
                                        </div>
                                    </a>
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
                                <div class="menu_triangle fcenter"></div>
                                <div class="menu_triangle_contend acenter">
                                    <a href="#">
                                        <div class="col-xs-12 brand-botao-rev2 brand-trans">
                                            CHAT
                                        </div>
                                    </a>
                                    <a href="#">
                                        <div class="col-xs-12 brand-botao-rev2 brand-trans">
                                            MENSAGEM
                                        </div>
                                    </a>
                                    <a href="#">
                                        <div class="col-xs-12 brand-botao-rev2 brand-trans">
                                            EMAIL
                                        </div>
                                    </a>
                                    <a href="#">
                                        <div class="col-xs-12 brand-botao-rev2 brand-trans">
                                            SKYPE
                                        </div>
                                    </a>
                                    <a href="#">
                                        <div class="col-xs-12 brand-botao-rev2 brand-trans">
                                            TELEFONE
                                        </div>
                                    </a>
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
                                <div class="menu_triangle fcenter"></div>
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
                                    <div class="col-xs-12 fcenter separator-line"></div>
                                    <a href="#">
                                        <div class="col-xs-12 brand-botao-rev2 brand-trans">
                                            ASPETO MODERNO
                                        </div>
                                    </a>
                                    <a href="#">
                                        <div class="col-xs-12 brand-botao-rev2 brand-trans">
                                            ASPETO CLÁSSICO
                                        </div>
                                    </a>
                                    <div class="clear"></div>
                                </div>
                            </div>
                        </div>
                    </ul>
                </div>
            </div><!--/.nav-collapse -->
        </div>
    </nav>
    <nav class="navbar navbar-default navbar-static-top navbar-2nd">
        <div class="col-xs-1">
            <a class="navbar-brand" rel="home" href="/" title="Bet Portugal">
                <img alt="ibetup" src="/assets/portal/img/favicon.png" />
            </a>
        </div>
        <div class="col-xs-5">
            <ul class="nav navbar-nav nav-onscroll">
                <li class="active"><a href="/aovivo">AO-VIVO</a></li>
                <li><a href="/desportos">DESPORTOS</a></li>
                <li><a href="/casino">CASINO</a></li>
                <li><a href="#vegas">VEGAS</a></li>
            </ul>
            @if(! $authUser)
                <a href="/registar" class="btn btn-brand btn-slim fright">REGISTAR</a>
            @else
                <div class="options fright">
                    <a class="optiontype btn btn-brand btn-slim fright">{{ $authUser->username }}</a>
                    <div class="menu_header menu_user animated fadeIn clear">
                        <div class="menu_triangle fcenter"></div>
                        <div class="menu_triangle_contend acenter">
                            <div class="col-xs-12 acenter">
                                <p class="brand-color2"><b class="brand-color">ID</b>{{ Auth::user()->internalId() }}</p>
                            </div>
                            <a href="/apostas">
                                <div class="col-xs-12 brand-botao-rev2 brand-trans">
                                    MINHAS APOSTAS
                                </div>
                            </a>
                            <a href="/comunicacao/mensagens">
                                <div class="col-xs-12 brand-botao-rev2 brand-trans">
                                    MENSAGENS
                                </div>
                            </a>
                            <a href="/logout">
                                <div class="col-xs-12 brand-botao-rev2 brand-trans">
                                    SAIR
                                </div>
                            </a>
                            <div class="clear"></div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        <div class="col-xs-4">
            @if(! $authUser)
                <button id="btnLogin" class="btn btn-brand btn-slim">LOGIN</button>
                {!! Form::open(array('route' => array('login'),'id' => 'saveLoginForm')) !!}
                <div class="col-xs-4">
                    <input name="username" id="user-login" type="text" class="requred botao-registar brand-back" placeholder="username" />
                </div>
                <div class="col-xs-4">
                    <input name="password" id="pass-login" type="password" class="required botao-registar brand-back" placeholder="password" />
                </div>
                <input id="submit-login" type="submit" class="btn btn-brand btn-login-sub col-xs-1 formLoginSubmit" value="OK" />
                <a href="/recuperar_password" class="btn btn-link col-xs-3">Recuperar dados</a>
                {!! Form::close() !!}
            @else
                <div class="options">
                    <a class="optiontype btn btn-brand btn-slim">
                        <span class="balance">{{ $authUser->balance->balance_available }}</span> EUR
                    </a>
                    <div class="menu_header menu_account animated fadeIn clear">
                        <div class="menu_triangle fcenter"></div>
                        <div class="menu_triangle_contend acenter">
                            <div class="col-xs-12 brand-title brand-color aleft">
                                Saldo Disponível
                            </div>
                            <div class="brand-descricao bborder neut-border mini-bpadding mini-mbottom aleft">
                                {{ $authUser->balance->balance_available }} EUR
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
                            <div class="brand-descricao bborder neut-border mini-bpadding mini-mbottom aleft">
                                {{ $authUser->balance->balance_available + $authUser->balance->bonus}}  EUR
                            </div>
                            <a href="/perfil">
                                <div class="col-xs-12 brand-botao-rev2 brand-trans">
                                    PERFIL
                                </div>
                            </a>
                            <a href="/promocoes">
                                <div class="col-xs-12 brand-botao-rev2 brand-trans">
                                    PROMOÇÕES
                                </div>
                            </a>
                            <a href="/banco/depositar">
                                <div class="col-xs-12 brand-botao-rev2 brand-trans">
                                    DEPOSITAR
                                </div>
                            </a>
                            <a href="/banco/levantar">
                                <div class="col-xs-12 brand-botao-rev2 brand-trans">
                                    LEVANTAR
                                </div>
                            </a>
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
                    <div class="brand-link board-menu-option">
                        <select>
                            <option value="English" selected="selected">English</option>
                            <option value="Português">Português</option>
                        </select>
                    </div>
                </div>
                <div class="board-menu-div">
                    <a class="optiontype">
                        <div class="brand-link board-menu-option">
                            <i class="fa fa-question"></i>
                        </div>
                    </a>
                    <div class="menu_header menu_comunica animated fadeIn">
                        <div class="menu_triangle fcenter"></div>
                        <div class="menu_triangle_contend acenter">
                            <a href="/definicoes">
                                <div class="col-xs-12 brand-botao-rev2 brand-trans">
                                    DEFINIR OPÇÕES
                                </div>
                            </a>
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
                        <div class="menu_triangle fcenter"></div>
                        <div class="menu_triangle_contend acenter">
                            <a href="#">
                                <div class="col-xs-12 brand-botao-rev2 brand-trans">
                                    CHAT
                                </div>
                            </a>
                            <a href="#">
                                <div class="col-xs-12 brand-botao-rev2 brand-trans">
                                    MENSAGEM
                                </div>
                            </a>
                            <a href="#">
                                <div class="col-xs-12 brand-botao-rev2 brand-trans">
                                    EMAIL
                                </div>
                            </a>
                            <a href="#">
                                <div class="col-xs-12 brand-botao-rev2 brand-trans">
                                    SKYPE
                                </div>
                            </a>
                            <a href="#">
                                <div class="col-xs-12 brand-botao-rev2 brand-trans">
                                    TELEFONE
                                </div>
                            </a>
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
                        <div class="menu_triangle fcenter"></div>
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
                            <div class="col-xs-12 fcenter separator-line"></div>
                            <a href="#">
                                <div class="col-xs-12 brand-botao-rev2 brand-trans">
                                    ASPETO MODERNO
                                </div>
                            </a>
                            <a href="#">
                                <div class="col-xs-12 brand-botao-rev2 brand-trans">
                                    ASPETO CLÁSSICO
                                </div>
                            </a>
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
