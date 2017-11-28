import store from '../common/store/store';

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
        'cookies-consent': require('./../common/components/cookies-consent.vue'),
        'register': require('./../common/components/register.vue')
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
