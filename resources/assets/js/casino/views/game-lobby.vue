<template>
    <div class="game-lobby" v-if="game">
        <div class="header">
            <span class="title">{{game.name}}</span>
            <i class="cp-cross" @click="quit()"></i>
            <div ref="favorite" :id="game.id" :game="game"></div>
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
                Store.games.getGameById(gameId)
                    .then(x => this.game = x);
            },
          
            quit: function() {
                router.push('/');
            },
        },
        computed: {
            isAuthenticated: function () {
                return Store.user.isAuthenticated;
            }
        },
        watch: {
            $route: function(to) {
                if (to.path.includes('/game-lobby/')) {
                    this.setGame(to.params.gameid);

                    this.$nextTick(() => $(window).scrollTop(0));
                }
            }
        },
        components: {
            'favorite': require('../components/favorite.vue')
        },
        mounted() {
            this.setGame(this.$route.params.gameid);
            this.$refs.favorite.checkClass();
          
        }
    }
    
</script>

<style lang="scss">
    @import '../../../sass/common/variables';

    .game-lobby {
        width: 100%;
        float: left;
        background-color: #2d415c;
        font-family: "Open Sans", "Droid Sans", Verdana, sans-serif;

        .header {
            font-family: "Exo 2", "Open Sans", "Droid Sans", sans-serif;
            font-weight: bold;
            font-size: 18px;
            color: #FFF;
            cursor: pointer;
            overflow: auto;
            padding: 7px 0 7px 20px;

            a {
                color: #FFF;
                text-decoration: none;

                &.selected {
                    color: #ff9900;
                }

                i {
                    font-size: 15px;
                }
            }

            i {
                font-size: 14px;
                padding: 6px 20px 0 0;
                float: right;
            }
        }

        .content {
            padding: 10px 20px;

            img {
                width: 100%;
            }
            .description {
                p {
                    padding: 10px 20px;
                    color: #FFF;
                }
            }
        }

        .footer {
            padding: 0 20px 40px;
            overflow: hidden;

            .button-container {
                float: left;
                width: 50%;

                button {
                    border-radius: 0;
                    color: #FFF;
                    width: 100%;
                    max-width: 160px;
                    height: 30px;

                }

                .register {
                    border: 1px solid #C69A31;
                    background-color: #C69A31;
                }

                .demo, .play {
                    background: none;
                    border: 1px solid #FFF;
                }
            }

            .button-container:nth-child(1) {
                text-align: right;
                padding-right: 5px;
            }
            .button-container:nth-child(2) {
                text-align: left;
                padding-left: 5px;
            }
        }
    }

    @media (min-width: $mobile-screen-width) {
        .game-lobby {
            width: 640px;
        }
    }
</style>