window.Vue = require('vue');

Vue.component('betslip', require('./components/betslip.vue'));
Vue.component('promotions', require('./../common/components/promotions.vue'));

window.Vuex = require('vuex');

Vue.use(Vuex);

Vue.config.ignoredElements = ['router-link'];

import promotions from './store/promotions';

window.Store = new Vuex.Store({
    modules: {
        promotions
    }
});

const app = new Vue({
    el: '.bet',
    mounted() {
        LeftMenu.init();
        Info.init();
        Search.init();
        Favorites.init();
        Betslip.init();
    }
});
