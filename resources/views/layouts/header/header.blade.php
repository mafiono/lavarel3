<!---- TOP BAR ---->
<div class="bs-wp">
    <nav class="navbar navbar-default navbar-static-top" style="background-color: #1e293e !important;">
        <div style="width: 1200px; margin: 0 auto;">
            <div class="navbar-header">
                <router-link to="/">
                    <a class="navbar-brand" rel="home" href="/" title="Casino Portugal">
                        <img alt="CasinoPortugal" src="/assets/portal/img/Logo-CP.svg" />
                    </a>
                </router-link>
            </div>
            <div id="navbar" class="navbar-menu">
                <ul class="nav" style="float: none;">
                    @include('layouts.header.menu', [
                        'live' => strpos(Request::url(), 'direto') !== false,
                        'casino' => strpos(Request::url(), 'casino') !== false,
                        'golodeouro' => strpos(Request::url(), 'golodeouro') !== false,
                        'sports' => strpos(Request::url(), 'direto') === false && strpos(Request::url(), 'casino') === false
                    ])
                </ul>
                <div class="navbar-fright">
                    @include('layouts.header.top_right')
                </div>
            </div><!--/.nav-collapse -->
        </div>
    </nav>
    <nav class="navbar navbar-default navbar-static-top navbar-2nd container-fluid" style="background-color: #1e293e !important;">
        <div class="clearfix" style="width: 1200px; margin: 0 auto;">
            <div class="col-xs-6" style="padding-right: 5px">
                <router-link to="/">
                    <a class="navbar-brand nav-onscroll" rel="home" href="/" title="Casino Portugal">
                        <img alt="CasinoPortugal" src="/assets/portal/img/Logo-CP.svg" />
                    </a>
                </router-link>
                <ul class="nav navbar-nav nav-onscroll">
                    @include('layouts.header.menu', [
                        'live' => false,
                        'casino' => false,
                        'golodeouro' => false,
                        'sports' => false
                    ])
                </ul>

            </div>
            <div class="col-xs-3" id="form-auth" >
                <div class="row">
                    <div class="col-xs-6 " id="form-register" >
                        @if(! $authUser)
                            <router-link to="/registar">
                                <a href="/registar" class="btn btn-brand btn-slim fright" title="Registar" style="padding-top:6px; font-size: 14px; background: #ff9900; border: 0">REGISTAR</a>
                            </router-link>
                        @else
                            <div class="options fright">
                                <router-link to="/perfil">
                                    <a href="/perfil" class="optiontype btn btn-brand btn-slim fright" style="padding-top:6px; font-size: 14px; background: #ff9900; border: 0">{{ $authUser->username }}</a> <a href="/perfil/comunicacao/mensagens"><span style="position:absolute;left:106px;" class="label label-default label-as-badge messages-count"></span></a>
                                </router-link>
                            </div>
                            <div id="user-id" class="hidden">{{ $authUser->internalId() }}</div>
                        @endif
                    </div>
                    <div class="col-xs-6" id="form-login">
                        @if(! $authUser)
                            <button id="btnLogin" class="btn btn-brand btn-slim" title="Login" style="vertical-align: top; font-size: 14px">LOGIN</button>
                            {!! Form::open(array('route' => array('login'),'id' => 'saveLoginForm')) !!}
                            <div class="error-placer no-error">
                                <div class="col-xs-4">
                                    <input name="username" id="user-login" type="text"
                                           class="required botao-registar brand-back"
                                           onblur="if(this.placeholder=='' && this.value=='')this.placeholder='Utilizador'"
                                           onfocus="this.placeholder=''; this.value=''"
                                           placeholder="Utilizador"
                                    />
                                </div>
                                <div class="col-xs-4">
                                    <input name="password" id="pass-login" type="password"
                                           class="required botao-registar brand-back"
                                           onblur="if(this.placeholder=='' && this.value=='')this.placeholder='Palavra-Passe'"
                                           onfocus="this.placeholder=''; this.value=''"
                                           placeholder="Palavra-Passe"
                                    />
                                </div>
                                <div class="col-xs-4">
                                    <input id="submit-login" type="submit" class="btn btn-brand btn-login-sub col-xs-4 formLoginSubmit" value="OK" />
                                    <a href="#" class="btn btn-link col-xs-6" id="btn_reset_pass" title="Recuperar dados">Recuperar dados</a>
                                </div>
                            </div>
                            {!! Form::close() !!}
                        @else
                            <div class="options">
                                <balance-button initial-balance="{{ $authUser->balance->balance_total }}"></balance-button>
                                <a href="/logout" class="btn btn-link logout" title="Sair" style="font-size: 12px; padding: 14px 0 0 15px; color: #ff9900;">Sair</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-xs-2 nav-ontop">
                @if ($casino)
                    <a href="javascript:" class="btn btn-clean fright" id="btn-search"><i class="cp-search" title="Pesquisar"></i></a>
                    <search></search>
                @else
                    <a href="javascript:void(0)" class="btn btn-clean fright" id="btn-search"><i class="cp-search" title="Pesquisar"></i></a>
                    <form id="searchForm"><input id="textSearch" type="text" class="botao-registar brand-back" placeholder="Procurar"></form>
                @endif
            </div>
        </div>
    </nav>
</div>


