<template>
    <div class="mobile-game-launcher">
        <div class="header">
            <span class="title">{{game.name}}</span>
            <i class="cp-cross" @click="quit()"></i>
            <favorite :id="game.id"></favorite>
        </div>
        <div class="content">
            <img :src="'/assets/portal/img/casino/games/' + game.image" :alt="game.name">
            <div class="description">
                <p>{{game.description}}</p>
            </div>
        </div>
        <div class="footer">
            <div class="button-container">
                <button class="play" @click.prevent="open()" v-if="isAuthenticated">JOGAR</button>
                <button class="register" @click.prevent="register()" v-else>REGISTAR</button>
            </div>
            <div class="button-container">
                <button class="demo" @click.prevent="demo()">DEMO</button>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                game: {},
            }
        },
        methods: {
            register: function() {
                router.push('/registar');
            },
            demo: function() {
                GameLauncher.demo(this.game.id);
            },
            open: function () {
                GameLauncher.open(this.game.id);
            },
            setGame: function(gameId) {
                this.game = this.$root.$data.games
                    .filter(game => game.id === gameId)[0];
            },
            quit: function() {
                page.back('/');
            },
        },
        computed: {
            isAuthenticated: function () {
                return Store.getters['user/isAuthenticated'];
            }
        },
        watch: {
            $route: function(to) {
                if (to.path.includes('/mobile/launch/')) {
                    this.setGame(to.params.gameid);

                    $(window).scrollTop(0);
                }
            }
        },
        components: {
            'favorite': require('../components/favorite.vue')
        },
        mounted() {
            this.setGame(this.$route.params.gameid);
        }
    }
</script>