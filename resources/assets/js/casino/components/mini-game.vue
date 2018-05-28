<template>
    <div class="mini-game" v-if="loaded" style="margin-top: 5px;">
        <div v-if="logged && !show">
            <img src="/assets/portal/img/casino/games/mini-game-intro.jpg" alt="" style="width: 100%;">

            <div class="text">
                Experimente já o Diamond Wild
            </div>
            <div class="footer">
                <div class="button-container">
                    <button class="play" @click.prevent="open('game')">JOGAR</button>
                </div>
                <div class="button-container">
                    <button class="demo" @click.prevent="open('game-demo')">DEMO</button>
                </div>
            </div>
        </div>
        <iframe v-if="show" :src="url" frameborder="0" scrolling="no" width="100%" height="420px"></iframe>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                loaded: false,
                gameType: 'game-demo',
                openGame: false,
            }
        },
        computed: {
            show() {
                return (!this.logged && this.loaded)
                    || (this.logged && this.openGame);
            },
            logged() {
                return Store.user.isAuthenticated;
            },
            url() {
                return `/casino/${this.gameType}/6674`;
            }
        },
        methods: {
            open(type) {
                this.gameType = type;
                this.openGame = true;
            }
        },
        mounted() {
            setTimeout(() => this.loaded = !window.MobileHelper.isMobile(), 100);
        }
    }
</script>

<style lang="scss" scoped>
    .mini-game {
        position: relative;
        height: 420px;
    }
    .text {
        position: absolute;
        width: 100%;
        bottom: 55px;
    }
    .footer {
        position: absolute;
        bottom: 5px;
        padding: 5px 20px;
        overflow: hidden;
        width: 100%;

        .button-container {
            padding: 0 5px;
            float: left;
            width: 50%;

            button {
                border-radius: 0;
                color: #FFF;
                width: 100%;
                max-width: 160px;
                height: 30px;

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
</style>