<template>
    <div class="suggested-bets bs-wp" v-if="show">
        <div class="header">
            <i class="cp-Ativo-2"></i>
            <i class="cp-exclamation-circle" data-toggle="tooltip" data-placement="left" title="Aposta com rapidez e melhores odds na nossa chave múltipla. O possível retorno é calculado com base numa aposta de 20€."></i>
        </div>
        <div class="description" v-html="description"></div>
        <div class="footer">
            <span>Ganha até {{prize | currency}}</span>
            <a class="bet" @click.prevent="clickBet"><span>apostar</span><i class="cp-chevron-right"></i></a>
        </div>
    </div>
</template>
<script>
    export default{
        data() {
            return {
                clickable: true,
                description: "",
                selectionsIds: [],
                selections: [],
                betAmount: 20,
            };
        },
        methods: {
            clickBet() {
                if (!this.clickable)
                    return;

                this.clickable = false;

                setTimeout(() => {this.clickable = true;}, 2000);

                this.fetchDailyBet(this.addToBetslip);
            },
            addToBetslip() {
                let interval = 0;

                this.selections.forEach(selection => setTimeout(() => Betslip.add({
                    id: selection.id,
                    name: selection.name,
                    odds: selection.decimal,
                    marketName: selection.market.market_type.name,
                    marketId: selection.market_id,
                    gameId: selection.market.fixture.id,
                    gameName: selection.market.fixture.name,
                    gameDate: selection.market.fixture.start_time_utc,
                    sportId: selection.market.fixture.sport_id,
                    amount: 0,
                }), interval += 200));

                $("#betslip-multiAmount").val(this.betAmount);
            },
            fetchBets(callback) {
                $.getJSON(ODDS_SERVER + "selections?ids=" + this.selectionsIds.join(",") + "&with=market.fixture,market.marketType")
                    .done(data => {
                        this.selections = data.selections;

                        if (callback)
                            callback();
                    });
            },
            fetchDailyBet(callback) {
                $.getJSON("/daily-bet").done(data => {
                    this.description = data.description;
                    this.selectionsIds = data.selections;

                    this.fetchBets(callback);
                });
            }
        },
        computed: {
            show() {
                return this.selections.length > 0;
            },
            prize() {
                let total = this.betAmount;

                this.selections.forEach((selection) => {
                    total *= selection.decimal;
                });

                return total;
            }
        },
        watch: {
            show: function (visibility) {
                if (visibility) {
                    Vue.nextTick(() => $(".suggested-bets .cp-exclamation-circle").tooltip());
                }
            }
        },
        filters: {
            currency(value) {
                return value.toFixed(2) + "€";
            }
        },
        mounted() {
            setInterval(this.fetchDailyBet(), 300000);
        }
    }
</script>
