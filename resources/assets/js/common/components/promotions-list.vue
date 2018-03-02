<template>
    <div>
        <div class="header">
            <h3 class="title">Promoções</h3>
            <i class="cp-times" @click.prevent="exit()"></i>
        </div>
        <div class="tabs">
            <div class="tab" :class="selectedTabClass('sports')" @click="setPromoType('sports')">Desporto <i :class="selectedTabIconClass('sports')"></i></div>
            <div class="tab" :class="selectedTabClass('casino')" @click="setPromoType('casino')">Casino <i :class="selectedTabIconClass('casino')"></i></div>
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
                <h4>Lamentamos, mas neste momento não temos nenhuma promoção disponível. Novas promoções ocorrerão em breve, até já!</h4>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                promoType: this.type === 'casino' ? 'casino' : 'sports'
            };
        },
        methods: {
            exit() {
                page.back();
            },
            togglePromo(id) {
                if (id === this.selectedPromoId) {
                    Store.promotions.selected = null;

                    return;
                }

                Store.promotions.selected = Store.promotions.getPromoById(id);
            },
            setPromoType(type) {
                this.promoType = type;

                Store.promotions.selected = null;
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
            },
            selectedTabClass(type) {
                return this.promoType === type ? 'selected' : '';
            },
            selectedTabIconClass(type) {
                return this.promoType === type ? 'cp-caret-down' : 'cp-plus';
            }
        },
        computed: {
            selectedPromoId() {
                return Store.promotions.selected
                    ? Store.promotions.selected.id
                    : -1;
            },
            promotions() {
                return Store.promotions.getPromosByType(this.promoType);
            },
            loaded() {
                return Store.promotions.loaded;
            },
            hasNoPromos() {
                return this.loaded && !Store.promotions.promos.length;
            },
            showHr() {
                return this.selectedPromoId === -1;
            },
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
