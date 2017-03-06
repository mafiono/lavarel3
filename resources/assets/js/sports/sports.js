import Store from './js/store';

window.Store = new Store();

window.Vue = require('vue');

Vue.component('suggested-bets', require('./components/Suggestedbets.vue'));

const app = new Vue({
    el: '#betslip-container'
});
