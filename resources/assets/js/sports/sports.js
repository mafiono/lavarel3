import store from '../common/store/store';

store.init();
Vue.component('betslip', require('./components/betslip.vue'));
Vue.component('promotions', require('./../common/components/promotions.vue'));
Vue.component('golodeouro', require('./../common/components/golodeouro.vue'));
Vue.component('promotions-button', require('./components/promotions-button.vue'));
Vue.component('mobile-header', require('./../common/components/mobile-header.vue'));
Vue.component('mobile-betslip-button', require('./../common/components/mobile-betslip-button.vue'));
Vue.component('mobile-login', require('./../common/components/mobile-login.vue'));
Vue.component('mobile-menu', require('./../common/components/mobile-menu.vue'));
Vue.component('mobile-up-button', require('./../common/components/mobile-up-button.vue'));
Vue.component('mobile-bet-alert', require('./components/mobile-bet-alert.vue'));
Vue.component('mobile-search-bar', require('./../common/components/mobile-search-bar.vue'));
Vue.component('mobile-left-menu-header', require('./../common/components/mobile-left-menu-header.vue'));
Vue.component('promotions-bigodd', require('./../common/components/promotions-bigodd.vue'));
Vue.component('balance-button', require('./../common/components/balance-button.vue'));
Vue.component('cookies-consent', require('./../common/components/cookies-consent.vue'));

store.user.isAuthenticated = userAuthenticated;
store.user.username = username;
store.mobile.isMobile = $(window).width() < 767;
window.Vuex = require('vuex');

Vue.use(Vuex);

Vue.config.ignoredElements = ['router-link'];

import promotions from '../common/store/promotions';
import golodeouro from '../common/store/golodeouro';
import user from '../common/store/user';
import mobile from '../common/store/mobile';

window.Store = new Vuex.Store({
    modules: {
        golodeouro,
        promotions,
        user,
        mobile
    }
});

Store.commit('user/setAuthenticated', userAuthenticated);
Store.commit('user/setUsername', username);
Store.commit('mobile/setIsMobile', $(window).width() < 767);

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
        'register': require('./../common/components/register.vue'),
        'promotions-link': require('./../common/components/promotions-link.vue'),
        'favorites-button': require('./../common/components/favorites-button.vue'),
        'balance-button': require('./../common/components/balance-button.vue')
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
