require('./helpers/helpers');

require('./register/register');

require('./perfil/helpers/forms');
require('./perfil/perfil');
require('./perfil/perfil-history');

require('./casino/js/page');

require('./casino/js/profileRouter');

require('./common/js/terminalVerifier');

window.Vue = require('vue');

import isMobile from 'ismobilejs';

window.isMobile = isMobile;

import VueRouter from 'vue-router';

Vue.use(VueRouter);

window.router = new VueRouter({
    mode: 'history',
    base: '/casino',
    routes: [
        { path: '/', component: require('./casino/views/home.vue') },
        { path: '/registar/:step?', component: require('./casino/views/register.vue') },
        { path: '/perfil/banco/:sub?', component: require('./casino/views/profile.vue') },
        { path: '/perfil/bonus/:sub?', component: require('./casino/views/profile.vue') },
        { path: '/perfil/historico', component: require('./casino/views/profile.vue') },
        { path: '/perfil/comunicacao/:sub?', component: require('./casino/views/profile.vue') },
        { path: '/perfil/jogo-responsavel/:sub?', component: require('./casino/views/profile.vue') },
        { path: '/perfil/:sub?', component: require('./casino/views/profile.vue') },
        { path: '/info/:term?', name: 'info', component: require('./casino/views/info.vue') },
        { path: '/favorites', component: require('./casino/views/favorite-games.vue') },
        { path: '/pesquisa/:term?', component: require('./casino/views/search-games.vue') }
    ]
});

new Vue({
    el: '.bet',
    data: function () {
        return {
            categoriesMenu: {
                selectedCategory: null
            },
            categories: [
                {id: 'slot', name: "Slots", class: "cp-slots"},
                {id: 'cards', name: "Cartas", class: "cp-blackjack"},
                {id: 'roulette', name: "Roleta", class: "cp-roleta"},
                {id: 'poker', name: "Poker", class: "cp-clubs"}
            ],
            games: games,
            favorites: {},
            search: {
                query: '',
                games: []
            },
            info: {
                routes: {
                    "sobre_nos": '/textos/sobre_nos',
                    "termos_e_condicoes": '/textos/termos_e_condicoes',
                    "contactos": '/textos/contactos',
                    "bonus_e_promocoes":  '/textos/bonus_e_promocoes',
                    "faq": '/textos/faq',
                    "pagamentos": '/textos/pagamentos',
                    "politica_privacidade": '/textos/politica_priv',
                    "jogo_responsavel": '/textos/jogo_responsavel'
                },
                selected: "sobre_nos",
                content: ""
            },
            profile: {
                routes: {
                    '/perfil': {0: "/perfil", page: "perfil"},
                    '/perfil/info': {0: "/perfil/info", sub: "info", page: "perfil"},
                    '/perfil/autenticacao': {0: "/perfil/autenticacao", sub: "autenticacao", page: "perfil"},
                    '/perfil/codigos': {0: "/perfil/codigos", sub: "codigos", page: "perfil"},
                    '/perfil/banco/saldo': {0: "/perfil/banco/saldo", page: "banco", sub: "saldo"},
                    '/perfil/banco/depositar': {0: "/perfil/banco/depositar", page: "banco", sub: "depositar"},
                    '/perfil/banco/conta-pagamentos': {0: "/perfil/banco/conta-pagamentos", page: "banco", sub: "conta-pagamentos"},
                    '/perfil/banco/levantar': {0: "/perfil/banco/levantar", page: "banco", sub: "levantar"},
                    '/perfil/bonus/porusar': {0: "/perfil/bonus/porusar", page: "bonus", sub: "porusar"},
                    '/perfil/bonus/activos': {0: "/perfil/bonus/activos", page: "bonus", sub: "activos"},
                    '/perfil/bonus/utilizados': {0: "/perfil/bonus/utilizados", page: "bonus", sub: "utilizados"},
                    '/perfil/bonus/amigos': {0: "/perfil/bonus/amigos", page: "bonus", sub: "amigos"},
                    '/perfil/historico': {0: "/perfil/historico", page: "perfil", sub: "historico"},
                    '/perfil/comunicacao/mensagens': {0: "/perfil/comunicacao/mensagens", page: "comunicacao", sub: "mensagens"},
                    '/perfil/comunicacao/definicoes': {0: "/perfil/comunicacao/definicoes", page: "comunicacao", sub: "definicoes"},
                    '/perfil/comunicacao/reclamacoes': {0: "/perfil/comunicacao/reclamacoes", page: "comunicacao", sub: "reclamacoes"},
                    '/perfil/jogo-responsavel/limites': {0: "/perfil/jogo-responsavel/limites", page: "jogo-responsavel", sub: "limites"},
                    '/perfil/jogo-responsavel/autoexclusao': {0: "/perfil/jogo-responsavel/autoexclusao", page: "jogo-responsavel", sub: "autoexclusao"},
                    '/perfil/jogo-responsavel/last_logins': {0: "/perfil/jogo-responsavel/last_logins", page: "jogo-responsavel", sub: "last_logins"}
                }
            }
        }
    },
    methods: {
        fetchFavorites: function () {
            $.get("/casino/games/favorites")
                .done(function (favorites) {
                    favorites.forEach(function (favorite) {
                        this.$set(this.favorites, favorite.id, true);
                    }.bind(this))
                }.bind(this));
        }
    },
    props: [
        'message'
    ],
    components: {
        'featured': require('./casino/components/featured.vue'),
        'search': require('./casino/components/search.vue'),
        'favorites-button': require('./casino/components/favorites-button.vue'),
        'banner': require('./casino/components/banner.vue'),
        'left-menu': require('./casino/components/left-menu.vue'),
        'slider': require('./casino/components/slider.vue')
    },
    router: router,
    mounted: function() {
        if (userLoggedIn)
            this.fetchFavorites();
    }
});

