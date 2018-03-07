import store from '../common/store/store';
import isMobile from 'ismobilejs';

window.isMobile = isMobile;

store.init();

store.user.isAuthenticated = userAuthenticated;
store.user.username = username;
store.mobile.isMobile = $(window).width() < 767;

$(window).resize(() => {
    store.mobile.isMobile = $(window).width() < 767;
});

window.Store = store;

import Vue from 'vue';

window.Vue = Vue;

Vue.config.ignoredElements = ['router-link'];

new Vue({
    el: '.bet',
    data() {
        return Object.assign({}, Store);
    },
    components: {
        'betslip': require('./components/betslip.vue'),
        'promotions': require('./../common/components/promotions.vue'),
        'golodeouro': require('./../common/components/golodeouro.vue'),
        'promotions-button': require('./components/promotions-button.vue'),
        'mobile-header': require('./../common/components/mobile-header.vue'),
        'mobile-betslip-button': require('./../common/components/mobile-betslip-button.vue'),
        'mobile-login': require('./../common/components/mobile-login.vue'),
        'mobile-menu': require('./../common/components/mobile-menu.vue'),
        'mobile-up-button': require('./../common/components/mobile-up-button.vue'),
        'mobile-bet-alert': require('./components/mobile-bet-alert.vue'),
        'mobile-search-bar': require('./../common/components/mobile-search-bar.vue'),
        'mobile-left-menu-header': require('./../common/components/mobile-left-menu-header.vue'),
        'promotions-bigodd': require('./../common/components/promotions-bigodd.vue'),
        'promotions-endurance': require('./../common/components/promotions-endurance.vue'),
        'cookies-consent': require('./../common/components/cookies-consent.vue'),
        'register': require('./../common/components/register.vue'),
        'promotions-link': require('./../common/components/promotions-link.vue'),
        'favorites-button': require('./../common/components/favorites-button.vue'),
        'balance-button': require('./../common/components/balance-button.vue'),
        'footer-hider': require('./components/footer-hider.vue'),
        'app-store-popup': require('./../common/components/app-store-popup.vue')
    },
    mounted() {
        Breadcrumb.init();
        LeftMenu.init();
        Info.init();
        Search.init();
        Favorites.init();
        Betslip.init();
    }
});
