<div class="board-menu-div board-menu">
    <a class="btn btn-header">Português</a>
</div>
<div class="board-menu-div">
    <a class="optiontype">
        <a class="brand-link board-menu-option" href="/info">
            <i class="fa fa-question"></i>
        </a>
    </a>
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
            <a href="#" onclick="return showChat();" class="btn btn-menu brand-trans">Chat</a>
            <a href="#" class="btn btn-menu brand-trans">Mensagem</a>
            <a href="mailto:{{ env('TEST_MAIL') }}?subject={{ urlencode('Convite para jogar') }}&body={{ urlencode('Olá, vem jogar na BetPortugal (http://BetPortugal.pt).')}}" class="btn btn-menu brand-trans" target="_blank">Email</a>
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