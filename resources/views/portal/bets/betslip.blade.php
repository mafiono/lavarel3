<div id="betslip-container" class="betslip-container">
    <div class="betslip-box header">
        <button id="betslip-bulletinTab" class="betslip-tab header selected">BOLETIM</button>
        <button id="betslip-openBetsTab" class="betslip-tab header" {{empty($authUser) ? 'disabled' : ''}}>EM ABERTO</button>
    </div>
    <div id="betslip-bulletinContainer" class="betslip-content">
        <div class="betslip-box betType">
            <button id="betslip-simpleTab" class="betslip-tab betType selected corner">Simples &nbsp; <i class="i1 fa fa-caret-down betslip-icon-tab active"></i></button>
            <button id="betslip-multiTab" class="betslip-tab betType">Múltipla &nbsp; <i class="i1 fa fa-plus betslip-icon-tab inactive"></i></button>
         </div>
        <div id="betslip-noBets" class="betslip-box noBets">
            <p class="betslip-text noBets">
                @if (!empty($authUser))
                    Seleccione as odds e<br>veja aqui as suas apostas.
                @else
                    Efectue o seu login<br>para poder apostar!
                @endif
            </p>
        </div>
        <div>
            <div id="betslip-simpleContainer">
                <div id="betslip-simpleContent"></div>
                <div id="betslip-simpleFooter" class="betslip-box simpleFooter hidden">
                    <div class="betslip-box row">
                        <span class="betslip-text count"><span id="betslip-simpleCount">0</span> Apostas</span>
                        <span id="betslip-simpleTotal" class="betslip-text count amount">€ 0.00</span>
                    </div>
                    <div class="betslip-box row">
                        <span class="betslip-text profit"> Possível retorno</span>
                        <span id="betslip-simpleProfit" class="betslip-text profit amount"> € 500,00</span>
                    </div>
                </div>
            </div>
            <div id="betslip-multiContainer" class="hidden">
                <div id="betslip-multiBets-content">
                </div>
                <div id="betslip-multiFooter" class="hidden">
                    <div class="betslip-box multiFooter">
                        <div class="betslip-box row">
                            <span class="betslip-text amountLabel">Total Aposta</span>
                            <span class="pull-right">
                                € <input id="betslip-multiAmount" type="text" class="betslip-field amount" value="0">
                            </span>
                        </div>
                        <div class="acenter">
                            <span id="multiBet-text-error" class="betslip-text error"></span>
                        </div>
                        <div class="betslip-box row">
                            <span class="betslip-text oddsLabel">Total Odds</span>
                            <span id="betslip-multiOdds" class="betslip-text odds"></span>
                        </div>
                        <div class="betslip-box row">
                            <span class="betslip-text profit">Possível Retorno</span>
                            <div class="pull-right">
                                <span id="betslip-multiProfit" class="betslip-text profit amount">€ 0.00</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div>
            @if (!empty($authUser))
                <button id="betslip-submit" class="betslip-button submit" disabled>EFECTUAR APOSTA</button>
            @else
                <button id="betslip-login" class="betslip-button login">LOGIN/REGISTO</button>
            @endif
        </div>
    </div>
    <div id="betslip-openBetsContainer" class="betslip-content hidden"></div>
</div>