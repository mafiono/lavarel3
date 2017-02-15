<!---- TOP BAR ---->
<div class="bs-wp">
    <nav class="navbar navbar-default navbar-static-top" style="background-color: #1e293e !important;">
        <div style="width: 1200px; margin: 0 auto;">
            <div class="navbar-header">
                <a class="navbar-brand" rel="home" href="/" title="Casino Portugal">
                    <img alt="CasinoPortugal" src="/assets/portal/img/main_logo.png" />
                </a>
            </div>
            <div id="navbar" class="navbar-menu">
                <ul class="nav" style="float: none;">
                    @include('layouts.header.menu')
                </ul>
                <div class="navbar-fright">
                    @include('layouts.header.top_right')
                </div>
            </div><!--/.nav-collapse -->
        </div>
    </nav>
    <nav class="navbar navbar-default navbar-static-top navbar-2nd container-fluid" style="background-color: #1e293e !important;">
        <div class="clearfix" style="width: 1200px; margin: 0 auto;">
            <div class="col-xs-6">
                <ul class="nav navbar-nav nav-onscroll">
                    @include('layouts.header.menu')
                </ul>
                @if(! $authUser)
                    <a href="/registar" class="btn btn-brand btn-slim fright" title="Registar">Registar</a>
                @else
                    <div class="options fright">
                        <a class="optiontype btn btn-brand btn-slim fright">{{ $authUser->username }}&nbsp @if(\App\Lib\Notifications::getMensagens()>0) <span class="label label-default label-as-badge">{{\App\Lib\Notifications::getMensagens()}}</span> @endif </a>
                        <div class="menu_header menu_user animated fadeIn clear">
                            <div class="menu_triangle"></div>
                            <div class="menu_triangle_contend acenter">
                                <div class="col-xs-12 acenter">
                                    <p class="brand-color2"><b class="brand-color">ID</b><span id="user-id">{{ Auth::user()->internalId() }}</span></p>
                                </div>
                                <a href="/perfil" class="btn btn-menu brand-trans">Perfil</a>
                                <a href="/perfil/historico" class="btn btn-menu brand-trans">Minhas apostas</a>
                                <a href="/perfil/comunicacao/mensagens" class="btn btn-menu brand-trans">Mensagens &nbsp @if(\App\Lib\Notifications::getMensagens()>0) <span class="label label-default label-as-badge">{{\App\Lib\Notifications::getMensagens()}}</span> @endif </a>
                                <a href="/logout" class="btn btn-menu brand-trans">Sair</a>
                                <div class="clear"></div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            <div class="col-xs-4" id="form-login">
                @if(! $authUser)
                    <button id="btnLogin" class="btn btn-brand btn-slim" title="Login">Login</button>
                    {!! Form::open(array('route' => array('login'),'id' => 'saveLoginForm')) !!}
                    <div class="error-placer no-error">
                        <div class="col-xs-4">
                            <input name="username" id="user-login" type="text" onblur="if(this.placeholder=='' && this.value=='')this.placeholder='Utilizador'" onclick="this.placeholder=''; this.value=''" class="required botao-registar brand-back" style="background-color: #FFF" placeholder="utilizador" />
                        </div>
                        <div class="col-xs-4">
                            <input name="password" id="pass-login" type="password" onblur="if(this.placeholder=='' && this.value=='')this.placeholder='Palavra Passe'" onclick="this.placeholder=''; this.value=''" class="required botao-registar brand-back" placeholder="palavra passe" />
                        </div>
                        <div class="col-xs-4">
                            <input id="submit-login" type="submit" class="btn btn-brand btn-login-sub col-xs-4 formLoginSubmit" value="OK" />
                            <a href="#" class="btn btn-link col-xs-6" id="btn_reset_pass" title="Recuperar dados">Recuperar dados</a>
                        </div>
                    </div>
                    {!! Form::close() !!}
                @else
                    <div class="options">
                        <a class="optiontype btn btn-brand btn-slim">
                            <span id="headerBalance" class="balance">{{ number_format($authUser->balance->balance_total, 2, '.', ',') }}</span> EUR
                        </a>
                        <div class="menu_header menu_account animated fadeIn clear">
                            <div class="menu_triangle"></div>
                            <div class="menu_triangle_contend acenter">
                                <div class="links col-xs-6">
                                    <a href="/perfil/banco/depositar" class="btn btn-menu brand-trans">Depositar</a>
                                    <a href="/perfil/bonus/porusar" class="btn btn-menu brand-trans">Promoções</a>
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
                <a href="javascript:void(0)" class="btn btn-clean fright" id="btn-search"><i class="fa fa-search" title="Pesquisar"></i></a>
                <form id="searchForm"><input id="textSearch" type="text" class="botao-registar brand-back" placeholder="Procurar"></form>
                <a id="btnFavorites" href="#" class="btn btn-clean fright" title="Ver Favoritos"><i class="fa fa-star"></i></a>
            </div>
        </div>
    </nav>
</div>


