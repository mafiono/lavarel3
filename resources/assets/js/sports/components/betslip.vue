<template>
    <div style="position: relative;" class="noselect">
        <div class="betslip" v-bind:class="floatClass"  v-bind:style="{top: betslipTop + 'px'}">
            <div class="header">
                <button id="betslip-bulletinTab" class="tab selected">BOLETIM <span v-if="betsCount">({{betsCount}})</span></button>
                <button id="betslip-openBetsTab" class="tab" :disabled="!userAuthenticated">EM ABERTO</button>
            </div>
            <div id="betslip-bulletinContainer" class="content">
                <div class="header">
                    <button id="betslip-simpleTab" class="tab">Simples &nbsp; <i class="cp-plus"></i></button>
                    <button id="betslip-multiTab" class="tab">Múltipla &nbsp; <i class="i1 cp-plus"></i></button>
                </div>
                <div id="betslip-noBets" class="noBets">
                    <span v-if="userAuthenticated">
                        Selecione as cotas e<br>veja aqui as suas apostas.
                    </span>
                    <span v-else>
                    Efectue o seu login<br>para poder apostar!
                    </span>
                </div>
                <div>
                    <div id="betslip-simpleContainer">
                        <div id="betslip-simpleContent" class="bets-content simple"></div>
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
                    <div id="betslip-multiContainer">
                        <div id="betslip-multiBets-content" class="bets-content multiple">
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
                <div class="footer" v-if="userAuthenticated">
                    <button id="betslip-submit" class="submit" disabled>EFETUAR APOSTA</button>
                    <button id="betslip-accept" class="submit hidden">ACEITAR NOVAS COTAS</button>
                </div>
                <div class="footer" v-else>
                    <button id="betslip-login" class="login">LOGIN/REGISTO</button>
                </div>
            </div>
            <div id="betslip-openBetsContainer" class="content hidden"></div>
            <suggested-bets></suggested-bets>
            <mini-game v-if="showMiniGame"></mini-game>
            <mini-slider v-if="showMiniSlider"></mini-slider>
        </div>
    </div>
</template>
<script>
    let $betSlip = null, $pageFooter = null, $oldY = 0, $direction = null, $window = $(window);

    export default{
        data() {
            return {
                bets: Betslip.bets,
                height: 0,
                scrollY: 0,
                scrollHeight: 0,
                screenHeight: 0,
                floatClass: "",
                betslipTop: 0,
                showMiniGame: false,
                showMiniSlider: false,
                margin: 140,
            }
        },
        components: {
            'suggested-bets': require('./suggestedBets.vue'),
            'mini-slider': require('./mini-slider.vue'),
            'mini-game': require('../../casino/components/mini-game.vue'),
        },
        methods: {
            updateBetslip: function() {
                this.scrollY = window.scrollY; // Scroll Atual
                this.scrollHeight = this.computeScrollHeight(); // Altura total da Pagina
                this.screenHeight = this.computeScreenHeight(); // Altura do Ecrã
                this.betslipHeight = $betSlip !== null ? $betSlip.height() || 0 : 0; // Altura da Betslip
                if (this.scrollY !== $oldY) {
                    $direction = this.scrollY < $oldY ? 'up' : 'down';
                }
                let headerHeight = 73;
                let topOfPage = (this.scrollY > headerHeight ? this.scrollY - headerHeight : 0);
                let maxPosition = this.computeFooterTopPosition() - this.betslipHeight - 165;
                let betSlipCanFit = (this.screenHeight - headerHeight) > this.betslipHeight;

                if ($direction === 'up') {
                    if (this.betslipTop !== topOfPage) {
                        // Ajust top everytime we go up
                        this.betslipTop = topOfPage;
                    }
                } else if ($direction === 'down') {
                    // Check the position of the footer vs betslip
                    let betSlipFooterPos = this.betslipTop + this.betslipHeight + this.margin;
                    let screenFooterPos = this.scrollY + this.screenHeight;
                    if (betSlipCanFit) {
                        // the scroll is too close to bottom so it will pass footer, also betslip is lower than screen
                        this.betslipTop = topOfPage;
                    } else if (screenFooterPos > betSlipFooterPos) {
                        // Go down we have a blank area below
                        this.betslipTop = screenFooterPos - this.betslipHeight - this.margin;
                    }
                }
                if (this.betslipTop > maxPosition) {
                    if (this.betslipTop !== maxPosition) {
                        // We got to the bottom of the page
                        this.betslipTop = maxPosition;
                    }
                }
                this.floatClass = ((136 + this.betslipHeight + 500) > this.scrollHeight) ? "" : "float";
                $oldY = this.scrollY;
            },
            computeScrollHeight() {
                let body = document.body;
                let html = document.documentElement;

                return Math.max(body.scrollHeight, document.body.offsetHeight,
                    html.clientHeight, html.scrollHeight, html.offsetHeight);
            },
            computeScreenHeight() {
                return $window.height();
            },
            computeFooterTopPosition(){
                return $pageFooter ? $pageFooter.offset().top : 0;
            },
            setFooterVisibility(visible) {
                if (visible)
                    $pageFooter.show();
                else
                    $pageFooter.hide();
            }
        },
        computed: {
            userAuthenticated: function() {
                return userAuthenticated;
            },
            betsCount: function() {
                this.updateBetslip();

                return this.bets.length;
            },
            isViewingBetslip() {
                return Store.mobile.view === "betslip";
            }
        },
        watch: {
            isViewingBetslip: function (isViewing) {
                this.setFooterVisibility(!isViewing);
            }
        },
        created() {
            window.addEventListener('scroll', this.updateBetslip);
        },
        destroyed () {
            window.removeEventListener('scroll', this.updateBetslip);
        },
        mounted() {
            $betSlip = $(".betslip");
            $pageFooter = $('.page-footer');
            window.setInterval(this.updateBetslip.bind(this), 1000);
            this.showMiniGame = window.showMiniGame || false;
            this.showMiniSlider = window.showMiniSlider || false;
        }
    }
</script>
