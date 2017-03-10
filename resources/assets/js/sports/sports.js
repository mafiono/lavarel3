import Store from './js/store';

window.Store = new Store();

window.Vue = require('vue');

Vue.component('betslip', require('./components/betslip.vue'));

const app = new Vue({
    el: '#betslip-container'
});
