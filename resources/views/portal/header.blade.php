<!---- TOP BAR ---->
<div class="col-xs-12 brand-back header-top" style="min-width:950px;">
    <div class="col-xs-11 fcenter">
        <div class="col-xs-12 header">
            <a href="/">
                <div class="col-xs-2 main-logo fleft">
                    <img alt="ibetup" src="/assets/portal/img/main_logo.png" />
                </div>
            </a>
            <div class="col-xs-7 acenter fleft">
                <!---- MENU BAR ---->
                <div class="col-xs-12 acenter main-menu">
                    <ul>
                        <a href="/apostas/aovivo">
                            <li class="menu-li {{strcmp(Route::getCurrentRoute()->getPath(),"apostas/aovivo")?"":"menu-black-active"}}">AO-VIVO</li>
                        </a>
                        <a href="/apostas">
                            <li class="menu-li {{strcmp(Route::getCurrentRoute()->getPath(),"apostas/desportos")?"":"menu-black-active"}}">DESPORTOS</li>
                        </a>
                        <a href="/casino">
                            <li class="menu-li {{strcmp(Route::getCurrentRoute()->getPath(),"casino")?"":"menu-black-active"}}">CASINO</li>
                        </a>
                        <a href="#">
                            <li class="menu-li">VEGAS</li>
                        </a>
                        <a href="#">
                            <li class="menu-li">JOGOS</li>
                        </a>
                    </ul>
                </div>
            </div>
            <div class="col-xs-3 aright board-menu fleft">
                <div class="board-menu-div">
                    <div class="brand-link board-menu-option">
                        <select>
                            <option value="English" selected="selected">English</option>
                            <option value="Português">Português</option>
                        </select>
                    </div>
                </div>
                <div class="board-menu-div">
                    <a class="optiontype">
                        <div class="brand-link menu-li menu_faq board-menu-option">
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
                        <div class="brand-link menu-li board-menu-option">
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
                        <div class="brand-link menu-li board-menu-option">
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
            </div>
            <div class="clear"></div>
        </div>
    </div>
</div>
<div class="col-xs-12 brand-back" style="min-width:950px;">
    <div class="col-xs-11 fcenter">
        <div class="col-xs-9 aright fleft">
            @if(! $authUser)
                <a href="/registar">
                    <div class="col-xs-2 botao-registar acenter brand-trans">
                        registar
                    </div>
                </a>
                <div class="acenter login">
                    <div class="login-it">
                        {!! Form::open(array('route' => array('login'),'id' => 'saveLoginForm')) !!}
                        <input name="username" id="user-login" type="text" class="requred botao-registar brand-back" placeholder="username" />
                        <input name="password" id="pass-login" type="password" class="required botao-registar brand-back" placeholder="password" />
                        <input id="submit-login" type="submit" class="brand-rev-back brand-link formLoginSubmit" value="OK" />
                    </div>
                    <div class="login_messages aleft">
                        <div class="login-error error" style="display:none;"> </div>
                        {!! Form::close() !!}
                        <a href="/recuperar_password">
                            <div class="forgot">
                                Recuperar Dados
                            </div>
                        </a>
                    </div>
                </div>
            @else
                <div class="options aright">
                    <a class="optiontype">
                        <div class="col-xs-2 botao-registar brand-trans fcenter">
                            {{ $authUser->username }}
                        </div>
                    </a>
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
                    <a class="optiontype">
                        <div class="col-xs-2 botao-registar brand-trans fcenter">
                            <span class="balance">{{ $authUser->balance->balance_available }}</span> EUR
                        </div>
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
            <div class="clear"></div>
        </div>
        <div class="col-xs-3 aright menu-options fright">
            <div class="menu-pesquisa menu-li fright">
                <form action="" method="get">
                    <input id="searchbar" class="search-box" type="search" name="termo" placeholder="PESQUISAR..."/>
                    <input id="searchbotton" class="search-botton brand-link brand-rev-back" type="submit" value="OK" />
                </form>
            </div>
            <div id="favorites-open" class="menu-favoritos brand-link fright" style="cursor:pointer;">
                <i class="fa fa-star"></i>
                <span class="favoritos-box">
                    <div class="favoritos-contend">&nbsp;</div>
                </span>
            </div>
            <div class="clear"></div>
        </div>
        <div class="clear"></div>
    </div>
</div>
<div>
    <div id="favorites-container" class="favorites-container hidden"></div>
</div>