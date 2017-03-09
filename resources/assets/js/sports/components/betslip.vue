<template>
    <div style="position: relative;" class="noselect">
        <div class="betslip" v-bind:class="floatClass"  v-bind:style="{top: betslipTop}">
            <div class="header">
                <button id="betslip-bulletinTab" class="tab selected">BOLETIM <span v-if="betsCount">({{betsCount}})</span></button>
                <button id="betslip-openBetsTab" class="tab" disabled>EM ABERTO</button>
            </div>
            <div id="betslip-bulletinContainer" class="content">
                <div class="header">
                    <button id="betslip-simpleTab" class="tab">Simples &nbsp; <i class="fa fa-plus"></i></button>
                    <button id="betslip-multiTab" class="tab">Múltipla &nbsp; <i class="i1 fa fa-plus"></i></button>
                </div>
                <div id="betslip-noBets" class="noBets">
                    <span v-if="userAuthenticated">
                        Seleccione as cotas e<br>veja aqui as suas apostas.
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
                    <button id="betslip-submit" class="submit" disabled>EFECTUAR APOSTA</button>
                    <button id="betslip-accept" class="submit hidden">ACEITAR NOVAS COTAS</button>
                </div>
                <div class="footer" v-else>
                    <button id="betslip-login" class="login">LOGIN/REGISTO</button>
                </div>
            </div>
            <div style="margin-top: 10px">
                <img src="assets/portal/img/demo/miniroullete.jpg">
            </div>
            <suggested-bets></suggested-bets>
            <div id="betslip-openBetsContainer" class="content hidden"></div>
        </div>
    </div>
</template>
<script>
    export default{
        data() {
            return {
                bets: Betslip.bets,
                height: 0,
                scrollY: 0,
                scrollHeight: 0,
                floatClass: "",
                betslipTop: 0
            }
        },
        components: {
            'suggested-bets': require('./suggestedBets.vue')
        },
        methods: {
            updateBetslip: function() {
                this.scrollY = window.scrollY;
                this.scrollHeight = document.body.scrollHeight;
                this.betslipHeight = $(".betslip").height() ? $(".betslip").height() : 0;

                if ((this.scrollY + this.betslipHeight + 450) > this.scrollHeight) {
                    this.betslipTop = (this.scrollHeight - this.betslipHeight - 500) + "px";
                } else {
                    this.betslipTop = (this.scrollY > 73 ? this.scrollY - 73 : 0) + "px";
                }

                this.floatClass = ((136 + this.betslipHeight + 500) > this.scrollHeight) ? "" : "float";
            },
        },
        computed: {
            userAuthenticated: function() {
                return userAuthenticated;
            },
            betsCount: function() {
                this.updateBetslip();

                return this.bets.length;
            },
        },
        created() {
            window.addEventListener('scroll', this.updateBetslip);
        },
        destroyed () {
            window.removeEventListener('scroll', this.updateBetslip);
        },
        mounted() {
            Betslip.init();

            window.setInterval(this.updateBetslip.bind(this), 1000);
        }
    }
</script>
