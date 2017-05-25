<template>
    <transition name="vue-fade-in">
        <div class="mobile-bet-alert" :class="action" v-if="show">
            <strong>{{msg}}</strong>
        </div>
    </transition>
</template>

<script>
    export default {
        data() {
            return {
                bets: Betslip.bets,
                betsCount: Betslip.bets.length,
                show: false,
                msg: "",
                action: "",
            };
        },
        methods: {
            showNotification() {
                if (this.isViewingBetslip
                    || (this.action === "add" && this.bet.origin && this.bet.origin === "storage")
                ) {
                    return;
                }

                this.show = true;

                setTimeout(() => this.show = false, 1200);
            }
        },
        computed: {
            bet() {
                return this.bets.length ? this.bets[this.bets.length - 1] : {};
            },
            isViewingBetslip() {
                return Store.getters['mobile/getView'] === "betslip";
            }
        },
        watch: {
            bets: function (newBets) {
                if (newBets.length > this.betsCount) {
                    this.action = "add";
                    this.msg = "Aposta adicionada.";
                } else {
                    this.action = "remove";
                    this.msg = "Aposta removida."
                }

                this.showNotification();

                this.betsCount = this.bets.length;
            }
        }
    }
</script>