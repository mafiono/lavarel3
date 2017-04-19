<template>
    <div>
        <div class="header">
            <h3 class="title">Promoções Desportivas</h3>
            <i class="cp-times" @click.prevent="exit()"></i>
        </div>
        <div style="overflow: hidden">
            <transition name="vue-slide-down">
                <div class="content" v-if="loaded" key="promotions">
                    <div class="promotion" v-for="promo in promotions">
                        <div class="synopsis" @click.prevent="togglePromo(promo.id)">
                            <img class="image" v-bind:src="promoImage(promo.image)" :alt="promo.title">
                            <h4 class="title">{{promo.title}}</h4>
                            <p class="text">{{synopsisText(promo.synopsis, promo.id)}}</p>
                        </div>
                        <div style="overflow: hidden">
                            <promotion :id="promo.id"></promotion>
                        </div>
                        <div :class="overlayClass(promo.id)" @click.prevent="togglePromo(promo.id)"></div>
                        <hr v-if="showHr">
                    </div>
                </div>
                <div class="content loading" v-else>
                    <i class="cp-spin cp-spinner5 cp-2x"></i>
                </div>
            </transition>
            <div class="content noPromos" v-if="hasNoPromos">
                <h4>Não existem promoções.</h4>
            </div>
        </div>
    </div>
</template>
<style>

</style>
<script>
    export default {
        data() {
            return {xx: false};
        },
        methods: {
            exit() {
                page.back();
            },
            togglePromo(id) {
                if (id === this.selectedPromoId) {
                    Store.commit('promotions/setSelected', null);

                    return;
                }
                Store.commit(
                    'promotions/setSelected',
                    Store.getters['promotions/getPromoById'](id)
                );
            },
            synopsisText(synopsis, id) {
                if (id === this.selectedPromoId)
                    return synopsis;

                return synopsis.substring(0, 260) + (synopsis.length < 260 ? "" : "...");
            },
            promoImage(image) {
                return "/promotions/get-image?image=" + image;
            },
            overlayClass(id) {
                return (this.selectedPromoId > -1 && id !== this.selectedPromoId) ? "overlay" : "";
            }
        },
        computed: {
            selectedPromoId() {
                return Store.state.promotions.selected
                    ? Store.state.promotions.selected.id
                    : -1;
            },
            promotions() {
                return Store.getters['promotions/getPromosByType'](this.type);
            },
            loaded() {
                return Store.state.promotions.loaded;
            },
            hasNoPromos() {
                return this.loaded && !Store.state.promotions.promos.length;
            },
            showHr() {
                return this.selectedPromoId === -1;
            }
        },
        props: ['type'],
        components: {
            'promotion': require('./promotion.vue')
        },
        mounted() {
            this.visible = !!this.collapsed;
        }
    }
</script>