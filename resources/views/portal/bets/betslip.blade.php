<div id="betslip-container" class="betslip">
    <div class="header">
        <button id="betslip-bulletinTab" class="tab selected">BOLETIM</button>
        <button id="betslip-openBetsTab" class="tab" {{empty($authUser) ? 'disabled' : ''}}>EM ABERTO</button>
    </div>
    <div id="betslip-bulletinContainer" class="content">
        <div class="header">
            <button id="betslip-simpleTab" class="tab">Simples &nbsp; <i class="fa fa-plus"></i></button>
            <button id="betslip-multiTab" class="tab">Múltipla &nbsp; <i class="i1 fa fa-plus"></i></button>
        </div>
        <div id="betslip-noBets" class="noBets">
                @if (!empty($authUser))
                    Seleccione as cotas e<br>veja aqui as suas apostas.
                @else
                    Efectue o seu login<br>para poder apostar!
                @endif
        </div>
        <div>
            <div id="betslip-simpleContainer">
                <div id="betslip-simpleContent"></div>
                <div id="betslip-simpleFooter" class="simpleFooter hidden">
                    <div class="row">
                        <span class="count"><span id="betslip-simpleCount">0</span> Apostas</span>
                        <span id="betslip-simpleTotal" class="amount">€ 0.00</span>
                    </div>
                    <div class="row">
                        <span class="profit"> Possível Retorno</span>
                        <span id="betslip-simpleProfit" class="profit amount"> € 500,00</span>
                    </div>
                </div>
            </div>
            <div id="betslip-multiContainer" class="hidden">
                <div id="betslip-multiBets-content">
                </div>
                <div id="betslip-multiFooter" class="hidden">
                    <div class="multiFooter">
                        <div class="row">
                            <span>Total Aposta</span>
                            <span class="amount">
                                € <input id="betslip-multiAmount" type="text" value="0">
                            </span>
                        </div>
                        <div class="acenter">
                            <span id="multiBet-text-error" class="error"></span>
                        </div>
                        <div class="row">
                            <span class="oddsLabel">Total Cotas</span>
                            <span id="betslip-multiOdds" class="odds"></span>
                            <span id="betslip-multiOldOdds" class="odds old"></span>
                        </div>
                        <div class="betslip-box row">
                            <span class="profit">Possível Retorno</span>
                            <div class="amount">
                                <span id="betslip-multiProfit" class="profit">€ 0.00</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer">
            @if (!empty($authUser))
                <button id="betslip-submit" class="submit" disabled>EFECTUAR APOSTA</button>
                <button id="betslip-accept" class="submit hidden">ACEITAR NOVAS COTAS</button>
            @else
                <button id="betslip-login" class="login">LOGIN/REGISTO</button>
            @endif
        </div>
    </div>
    <suggested-bets></suggested-bets>
    <div id="betslip-openBetsContainer" class="content hidden"></div>
</div>