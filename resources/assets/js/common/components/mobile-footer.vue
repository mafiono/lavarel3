<template>
    <transition name="vue-fade-in">
        <div class="mobile-footer" @click="toggleBetslip()">
            <div class="tab" >
                <span v-if="!viewingBetslip">{{betCount}}</span>
                <i class="cp-times" v-else></i>
            </div>
        </div>
    </transition>
</template>
<style>

</style>
<script>
    export default {
        data() {
            return {
                bets: Betslip.bets
            }
        },
        methods: {
            toggleBetslip() {
                this.viewingBetslip ? MobileHelper.showContent() : MobileHelper.showBetslip();
            }
        },
        computed: {
            viewingBetslip() {
                return Store.getters['mobile/getView'] === "betslip";
            },
            betCount() {
                return this.bets.length;
            }
        }
    }
</script>