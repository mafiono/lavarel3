
<div class="col-xs-3 acenter fleft" >

    <div class="apostas-box apostas-options grey-back" style="overflow: auto; clear: both;">

        <div class="rightBar">

            <div class="col-xs-5 jogo-box-botao blue-hover inline height1 blue-active boletim">BOLETIM</div>

            @if (!empty($authUser))

            <div class="col-xs-5 jogo-box-botao blue-hover inline height1 pendentes">APOSTAS</div>

            @endif

        </div>





        <div style="position:relative; display: none" id="boletim">

            <div class="col-xs-5 boletim-opcao inline" id="simpleBets">

                <input type="radio" id="aposta-simples" name="boletim-opcao" checked>

                <label for="aposta-simples" class="col-xs-12 jogo-box-botao blue-hover height1">Simples</label>

            </div>

            <div class="col-xs-5 boletim-opcao inline" id="multipleBets">

                <input type="radio" id="aposta-multipla" name="boletim-opcao">

                <label for="aposta-multipla" class="col-xs-12 jogo-box-botao blue-hover height1">Multipla</label>

            </div>

            <div id="bets-container" style="margin-top: 12px;"></div>



            <div id="bets-multiple-input-container" class="col-xs-12 aposta-box-botao tabela-bet-active hidden">

                <div class="col-xs-11 fcenter">

                    <div class="col-xs-8 aleft height1 fleft">Montante a Apostar</div>

                    <input id="bets-multiple-input" type="text" value="0" style="width:30px;color:#000;"/>

                </div>

                <div class="col-xs-3 yellow-color height2 aright fleft gameOdd" ></div>

                <span class="has-error betError" style="display:none;">Ocorreu um erro, aposta inválida.</span>

                <div class="clear"></div>

            </div><!-- END bets-multiple-input-container -->



            <div id="bets-total-container" class="hidden">

                <div id="totalBets" style="margin-top: 15px">

                    <div class="col-xs-12 aposta-box-botao grey2-back">

                        <div class="col-xs-11 fcenter">

                            <div class="col-xs-8 aleft height2 fleft">Total de Cotas</div>

                            <div class="col-xs-3 yellow-color height2 aright fleft totalOdd"><b></b></div>



                            <div class="col-xs-8 aleft height2 fleft">Aposta Total</div>

                            <div class="col-xs-3 height2 aright fleft totalBet"><b></b></div>



                            <div class="col-xs-8 aleft height2 fleft">Possivel Retorno</div>

                            <div class="col-xs-3 height2 aright fleft totalProfit"><b></b></div>



                            <div class="clear"></div>

                        </div>

                    </div>



                </div> <!-- END bets-total-container -->

                @if (!empty($authUser))

                <div class="col-xs-12 aposta-enviar-botao green-back brand-link height2 submitBet">

                    EFECTUAR APOSTA

                </div>

                @else

                <div class="col-xs-12 aposta-enviar-botao green-back brand-link height2">

                    EFECTUE LOGIN PARA APOSTAR

                </div>

                @endif



                <div class="col-xs-12 aright brand-link height2" id="clearAllBets">

                    Limpar Todos

                </div>

            </div>

        </div> <!-- END BOLETIM -->



        @if (!empty($authUser))



        <div style="position:relative; display:none; margin-top: 25px" id="pending-bets-container">

            <div class="col-xs-12 aposta-box-botao tabela-bet-active recentBet" style="display:none;">

                <div class="col-xs-11 fcenter">

                    <div class="col-xs-11 height2 aright fleft gameName"></div>

                    <div class="col-xs-3 aleft height1 fleft"><b>Data: </b></div>

                    <div class="col-xs-8 yellow-color height2 aright fleft gameDate"></div>

                    <div class="col-xs-3 aleft height1 fleft"><b>Aposta: </b></div>

                    <div class="col-xs-8 yellow-color height2 aright fleft userBet"></div>

                    <div class="col-xs-3 aleft height2 fleft"><b>Valor:</b></div>

                    <div class="col-xs-8 yellow-color height2 aright fleft gameBet"><b></b></div>

                    <div class="col-xs-3 aleft height2 fleft"><b>Odd:</b></div>

                    <div class="col-xs-8 yellow-color height2 aright fleft gameOdd"><b></b></div>

                    <div class="col-xs-3 aleft height2 fleft"><b>Ganhar:</b></div>

                    <div class="col-xs-8 yellow-color height2 aright fleft gameProfit"><b></b></div>

                    <div class="clear"></div>

                </div>

            </div>

        </div>

        @endif



    </div> <!-- END APOSTAS BOX -->

    <div class="clear"></div>

</div> <!-- END COLUMN 3 -->