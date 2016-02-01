<div id="bets-container" class="bets-container">
    {{--<iframe width="640" height="480" frameborder="0" scrolling="no"--}}
        {{--src="http://nogs-gl.nyxinteractive.eu/game/?nogsgameid=70001&nogsoperatorid=1--}}
        {{--&sessionid=HM5aFZqMTZGVU0vZUN&nogscurrency=eur&nogslang=en_us&nogsmode=real--}}
        {{--&accountid=12345"></iframe>--}}
    <div class="hBox">
        <button id="bets-button-betSlip" class="bets-button-tab bets-button-tab-selected" data-selected-css="bets-button-tab-selected">BOLETIM</button>
        <button id="bets-button-openBets" class="bets-button-tab" data-selected-css="bets-button-tab-selected" disabled>EM ABERTO</button>
    </div>
    <div id="betSlip-container">
        <div class="hBox vmargin-small">
            <button id="betSlip-simpleBets-tab" class="bets-button-betSlipTab bets-button-betSlipTab-selected" data-selected-css="bets-button-betSlipTab-selected">Simples</button>
            <button id="betSlip-multiBets-tab" class="bets-button-betSlipTab" data-selected-css="bets-button-betSlipTab-selected" disabled>Multipla</button>
            <button class="bets-button-betSlipTab" data-selected-css="bets-button-betSlipTab-selected" disabled>Sistema</button>
         </div>
        <div class="betSlip-content">
            <p id="betSlip-text-noBets" class="bets-text-noBets">Nenhuma aposta foi selecionada. Para seleccionar uma aposta, clique no respectivo resultado. Boa sorte.</p>
            <div id="betSlip-simpleBets-container"></div>
            <div id="betSlip-multiBets-container" class="hidden">
                <div id="betSlip-multiBets-content"></div>
                <div id="betSlip-multiBets-footer" class="hidden">
                    <div class="bets-box vmargin-small" style="background: #474445;">
                        <div class="hBox">
                            <span class="bets-text-label">Total de Cotas</span>
                            <span id="betSlip-multiBet-totalOdds" class="bets-text-odds"></span>
                        </div>
                        <div class="hBox">
                            <span class="bets-text-label-small">Montante a Apostar</span>
                            <input id="betSlip-multiBet-field-amount" type="text" class="bets-field-amount" value="0">
                        </div>
                        <div class="acenter">
                            <span id="multiBet-text-error" class="bets-text-error"></span>
                        </div>
                        <div class="hBox">
                            <span class="bets-text-profitLabel">Possivel Retorno</span>
                            <span id="betSlip-multiBet-totalProfit" class="bets-text-profit"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="betSlip-footer">
        @if (!empty($authUser))
            <button id="betSlip-button-submit" class="bets-button-submit" disabled>EFECTUAR APOSTA</button>
        @else
            <button id="betSlip-button-login" class="bets-button-submit">EFECTUE LOGIN PARA APOSTAR</button>
        @endif
            <div class="hBox aright">
                <button id="betSlip-button-clear" class="bets-button-reset" disabled>Limpar Todos</button>
            </div>
        </div>
    </div>
    <div id="openBets-container" class="openBets-container hidden"></div>
</div>