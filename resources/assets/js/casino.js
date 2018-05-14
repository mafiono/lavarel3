require('./helpers/helpers');

require('./register/register');

require('./perfil/helpers/forms');
require('./perfil/perfil');
require('./perfil/perfil-history');

require('./casino/js/profileRouter');
require('./casino/js/gameLauncher');


import store from './common/store/store';
store.games = require('./casino/js/games-store').default;
store.favorites = require('./casino/js/favorite-store').default;

store.init();

store.user.isAuthenticated = userAuthenticated;
store.user.username = username;
store.mobile.isMobile = $(window).width() < 767;

$(window).resize(() => {
    store.mobile.isMobile = $(window).width() < 767;
});

window.Store = store;

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
        { path: '/perfil/historico', component: require('./casino/views/profile.vue') },
        { path: '/perfil/comunicacao/:sub?', component: require('./casino/views/profile.vue') },
        { path: '/perfil/jogo-responsavel/:sub?', component: require('./casino/views/profile.vue') },
        { path: '/perfil/:sub?', component: require('./casino/views/profile.vue') },
        { path: '/info/:term?', name: 'info', component: require('./casino/views/info.vue') },
        { path: '/favoritos', component: require('./casino/views/favorite-games.vue') },
        { path: '/pesquisa/:term?', component: require('./casino/views/search-games.vue') },
        { path: '/mobile/login', component: require('./casino/views/mobile-login.vue') },
        { path: '/mobile/menu-casino', component: require('./casino/views/menu-casino.vue') },
        { path: '/mobile/menu', component: require('./casino/views/mobile-menu.vue') },
        { path: '/promocoes', component: require('./casino/views/promotions.vue') },
        { path: '/game-lobby/:gameid', component: require('./casino/views/game-lobby.vue') },
        { path: '/rondas-abertas', component: require('./casino/views/open-rounds.vue') },
    ]
});

Store.app.currentRoute = router.currentRoute.path;

require('./casino/js/page');

new Vue({
    el: '.bet',
    data: function () {
        return Object.assign({
            categoriesMenu: {
                selectedCategory: null
            },
            categories: [
                {id: 'slot', name: "Slots", class: "cp-slots"},
                {id: 'cards', name: "Cartas", class: "cp-blackjack"},
                {id: 'roulette', name: "Roleta", class: "cp-roleta"},
                {id: 'poker', name: "Poker", class: "cp-clubs"},
                {id: 'jackpot', name: "Jackpot", class: "cp-jackpots"}
            ],
            favorites: {},
            search: {
                query: '',
                games: []
            },
            info: {
                routes: {
                    "termos_e_condicoes": '/textos/termos_e_condicoes',
                    "contactos": '/textos/contactos',
                    "faq": '/textos/faq',
                    "pagamentos": '/textos/pagamentos',
                    "politica_privacidade": '/textos/politica_priv',
                    "jogo_responsavel": '/textos/jogo_responsavel'
                },
                selected: "termos_e_condicoes",
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
                    '/perfil/banco/conta-pagamentos': {
                        0: "/perfil/banco/conta-pagamentos",
                        page: "banco",
                        sub: "conta-pagamentos"
                    },
                    '/perfil/banco/levantar': {0: "/perfil/banco/levantar", page: "banco", sub: "levantar"},
                    '/perfil/historico': {0: "/perfil/historico", page: "perfil", sub: "historico"},
                    '/perfil/comunicacao/mensagens': {
                        0: "/perfil/comunicacao/mensagens",
                        page: "comunicacao",
                        sub: "mensagens"
                    },
                    '/perfil/comunicacao/definicoes': {
                        0: "/perfil/comunicacao/definicoes",
                        page: "comunicacao",
                        sub: "definicoes"
                    },
                    '/perfil/comunicacao/reclamacoes': {
                        0: "/perfil/comunicacao/reclamacoes",
                        page: "comunicacao",
                        sub: "reclamacoes"
                    },
                    '/perfil/jogo-responsavel/limites': {
                        0: "/perfil/jogo-responsavel/limites",
                        page: "jogo-responsavel",
                        sub: "limites"
                    },
                    '/perfil/jogo-responsavel/autoexclusao': {
                        0: "/perfil/jogo-responsavel/autoexclusao",
                        page: "jogo-responsavel",
                        sub: "autoexclusao"
                    },
                    '/perfil/jogo-responsavel/last_logins': {
                        0: "/perfil/jogo-responsavel/last_logins",
                        page: "jogo-responsavel",
                        sub: "last_logins"
                    }
                }
            }
        }, store)
    },
    methods: {
        mobileRedirect() {
            if (router.currentRoute.path.includes('/mobile') && !this.isMobile) {
                router.push('/');
            }
        },
        fetchFavorites() {
            let un = Store.favorites.store.subscribe(() => un.unsubscribe());
        },
        highlightCasinoNavLink() {
            if (Store.app.currentRoute !== '/favoritos') {
                $('.header-casino').addClass('active');

            }
        }
    },
    computed: {
        isMobile: function() {
            return Store.mobile.isMobile;
        }
    },
    props: [
        'message'
    ],
    components: {
        'featured': require('./casino/components/featured.vue'),
        'search': require('./casino/components/search.vue'),
        'banner': require('./casino/components/banner.vue'),
        'left-menu': require('./casino/components/left-menu.vue'),
        'slider': require('./casino/components/slider.vue'),
        'mobile-header': require('./common/components/mobile-header.vue'),
        'mobile-menu': require('./common/components//mobile-menu.vue'),
        'mobile-up-button': require('./common/components/mobile-up-button.vue'),
        'balance-button': require('./common/components/balance-button.vue'),
        'cookies-consent': require('./common/components/cookies-consent.vue'),
        'promotions-link': require('./common/components/promotions-link.vue'),
        'favorite-games' : require('./casino/views/favorite-games.vue'),
        'mobile-app-banner': require('./common/components/mobile-app-banner.vue')
    },
    router,
    watch: {
        $route: function () {
            Store.app.currentRoute = router.currentRoute.path;

            this.highlightCasinoNavLink();

            this.mobileRedirect();
        },
        isMobile: function() {
            this.mobileRedirect();
        },
    },
    mounted: function() {
       
            this.fetchFavorites();

        this.mobileRedirect();
    }
});

