<template>
    <div class="suggested-bets" v-if="show">
        <div class="header">
            <button class="header-tab selected">Aposta do dia &nbsp; <i class="cp-caret-down active"></i></button>
            <button class="header-tab">&nbsp;</button>
        </div>
        <div class="description">{{description}}</div>
        <div style="margin-top: 5px;">
            <button class="bet" @click="clickBet">APOSTAR</button>
        </div>
    </div>
</template>
<script>
    export default{
        data() {
            return {
                description: "",
                selections: []
            };
        },
        methods: {
            clickBet() {
                this.fetchBets();
            },
            fetchBets() {
                $.getJSON(ODDS_SERVER + "selections?ids=" + this.selections.join(",") + "&with=market.fixture")
                    .done(data => {
                        console.log(data);
                        var interval = 0;
                        data.selections.forEach(selection => setTimeout(() => Betslip.add({
                            id: selection.id,
                            name: selection.name,
                            odds: selection.decimal,
                            marketName: selection.market.name,
                            marketId: selection.market_id,
                            gameId: selection.market.fixture.id,
                            gameName: selection.market.fixture.name,
                            gameDate: selection.market.fixture.start_time_utc,
                            amount: 0,
                        }), interval += 200));
                    });
            },
            fetchDailyBet() {
                $.getJSON("/daily-bet").done(data => {
                    this.description = data.description;
                    this.selections = data.selections;
                });
            }
        },
        computed: {
            show() {
                return this.selections.length > 0;
            }
        },
        mounted() {
            this.fetchDailyBet();
        }
    }
</script>
