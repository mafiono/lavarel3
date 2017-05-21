<template>
    <transition name="vue-fade-in">
        <div class="mobile-footer" @click="toggleBetslip()" v-if="show">
            <div class="tab" >
                <span v-if="!isViewingBetslip">{{betCount}}</span>
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
                if (this.isViewingBetslip)
                    page.back();
                else {
                    page('/mobile/betslip');
                }

            }
        },
        computed: {
            show() {
                return this.isViewingBetslip || this.betCount;
            },
            isViewingBetslip() {
                return Store.getters['mobile/getView'] === "betslip";
            },
            betCount() {
                return this.bets.length;
            }
        }
    }
</script>