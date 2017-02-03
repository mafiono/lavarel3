require('./perfil/helpers/forms');

window.Vue = require('vue');

const VueRouter = require('vue-router');

Vue.use(VueRouter);

window.router = new VueRouter({
    mode: 'history',
    base: '/casino',
    routes: [
        { path: '/', component: require('./casino/views/home.vue') },
        { path: '/registar', component: require('./casino/views/register.vue') },
        { path: '/perfil/:term?', component: require('./casino/views/profile.vue') },
        { path: '/banco/:term?', component: require('./casino/views/profile.vue') },
        { path: '/promocoes/:term?', component: require('./casino/views/profile.vue') },
        { path: '/historico', component: require('./casino/views/profile.vue') },
        { path: '/comunicacao/:term?', component: require('./casino/views/profile.vue') },
        { path: '/jogo-responsavel/:term?', component: require('./casino/views/profile.vue') },
        { path: '/jogo-responsavel/limites/:term?', component: require('./casino/views/profile.vue') },
        { path: '/info/:term?', name: 'info', component: require('./casino/views/info.vue') },
        { path: '/favorites', component: require('./casino/views/favorite-games.vue') },
        { path: '/pesquisa/:term?', component: require('./casino/views/search-games.vue') }
    ]
});

window.app = new Vue({
    el: '.bet',
    data: function () {
        return {
            categoriesMenu: {
                selectedCategory: null
            },
            categories: [
                {id: 'slot', name: "Slots", class: "fa-table"},
                {id: 'cards', name: "Cartas", class: "fa-money"},
                {id: 'roulette', name: "Roleta", class: "fa-star"},
                {id: 'poker', name: "Poker", class: "fa-envelope"}
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
                src: "/perfil",
                iframePath: "",
                routes: [
                    '/perfil', '/perfil/autenticacao', '/perfil/codigo-pin',
                    '/banco/saldo', '/banco/depositar', '/banco/conta-pagamentos',
                    '/banco/levantar', '/promocoes', '/promocoes/activos',
                    '/promocoes/utilizados', '/historico', '/comunicacao/mensagens',
                    '/comunicacao/definicoes', '/comunicacao/reclamacoes', '/jogo-responsavel/limites/apostas',
                    '/jogo-responsavel/autoexclusao', '/jogo-responsavel/last_logins'
                ]
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

