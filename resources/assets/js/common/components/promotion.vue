<template>
    <transition name="vue-slide">
        <div v-if="show">
            <div style="overflow: hidden">
                <div class="body" v-bind:is="componentBodyId"></div>
                <div class="footer">
                    <a href="javascript:" @click.prevent="toggleTerms()"><i :class="termsIconClass"></i> Termos e Condições</a>
                    <button :class="actionClass" @click.prevent="performAction()" v-if="showActionButton">{{action}}</button>
                </div>
                <div style="overflow: hidden">
                    <transition name="vue-slide">
                        <div class="terms" v-if="termsVisible" v-html="promo.terms"></div>
                    </transition>
                </div>
            </div>
        </div>
    </transition>
</template>
<script>
    export default {
        data() {
            return {
                termsVisible: false,
            }
        },
        methods: {
            toggleTerms() {
                this.termsVisible = !this.termsVisible;
            },
            performAction() {
                if (!userAuthenticated) {
                    page('/registar');
                    return;
                }
                page(this.promo.action_url);
            }
        },
        computed: {
            show() {
                return Store.promotions.selected
                    && Store.promotions.selected.id === this.id;
            },
            promo() {
                return Store.promotions.selected;
            },
            termsIconClass() {
                return this.termsVisible ? 'cp-caret-down' : 'cp-plus';
            },
            showActionButton() {
                return !userAuthenticated || this.promo.action;
            },
            action() {
                return userAuthenticated ? this.promo.action : 'REGISTAR-SE';
            },
            actionClass() {
                return userAuthenticated ? 'action' : 'register';
            },
            componentBodyId() {
                return 'promotion-body-' + this.id;
            }
        },
        props: [
            'id'
        ],
        mounted() {
            Vue.component('promotions-bigodd', require('../../common/components/promotions-bigodd.vue'));
            Vue.component('promotions-endurance', require('../../common/components/promotions-endurance.vue'));
        }
    }
</script>

