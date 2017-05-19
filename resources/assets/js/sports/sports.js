window.Vue = require('vue');

Vue.component('betslip', require('./components/betslip.vue'));
Vue.component('promotions', require('./../common/components/promotions.vue'));
Vue.component('promotions-button', require('./components/promotions-button.vue'));
Vue.component('mobile-header', require('./../common/components/mobile-header.vue'));
Vue.component('mobile-footer', require('./../common/components/mobile-footer.vue'));
Vue.component('mobile-login', require('./../common/components/mobile-login.vue'));
Vue.component('mobile-menu', require('./../common/components/mobile-menu.vue'));

window.Vuex = require('vuex');

Vue.use(Vuex);

Vue.config.ignoredElements = ['router-link'];

import promotions from './store/promotions';
import user from './store/user';
import mobile from './store/mobile';

window.Store = new Vuex.Store({
    modules: {
        promotions,
        user,
        mobile
    }
});

Store.commit('user/setAuthenticated', userAuthenticated);
Store.commit('user/setUsername', username);

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
