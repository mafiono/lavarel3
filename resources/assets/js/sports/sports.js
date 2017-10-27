window.Vue = require('vue');

Vue.component('betslip', require('./components/betslip.vue'));
Vue.component('promotions', require('./../common/components/promotions.vue'));
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
Vue.component('cookies-consent', require('./../common/components/cookies-consent.vue'));

window.Vuex = require('vuex');

Vue.use(Vuex);

Vue.config.ignoredElements = ['router-link'];

import promotions from '../common/store/promotions';
import user from '../common/store/user';
import mobile from '../common/store/mobile';

window.Store = new Vuex.Store({
    modules: {
        promotions,
        user,
        mobile
    }
});

Store.commit('user/setAuthenticated', userAuthenticated);
Store.commit('user/setUsername', username);
Store.commit('mobile/setIsMobile', $(window).width() < 767);

$(window).resize(() => {
    Store.commit('mobile/setIsMobile', $(window).width() < 767);
});

const app = new Vue({
    el: '.bet',
    mounted() {
        Breadcrumb.init();
        LeftMenu.init();
        Info.init();
        Search.init();
        Favorites.init();
        Betslip.init();
    }
});
