<template>
    <div class="game">
        <div style="position: relative; z-index:1" v-show="game.is_new">
            <span class="tag">Novo</span>
        </div>
        <div style="position: relative; z-index:1" v-show="!userLoggedIn">
            <button class="game-btn play" @click="open">Jogar</button>
        </div>
        <div style="position: relative; z-index:1" v-show="!userLoggedIn">
            <button class="game-btn demo" @click="demo">Demo</button>
        </div>
        <game-thumb-link :game="game" width="217px"></game-thumb-link>
        <div v-if="!hideDescription">
            <span class="name">{{game.name}}</span>
            <favorite :id="game.id"></favorite>
        </div>
    </div>
</template>

<script>
    export default{
        props: ['game', 'hideDescription'],
        methods: {
            open: function() {
                if (Store.getters['mobile/getIsMobile']) {
                    router.push(`/mobile/launch/${this.game.id}`);
                } else if (this.userLoggedIn) {
                    GameLauncher.open(this.game.id);
                } else
                    router.push('/registar');
            },
            demo: function() {
                GameLauncher.demo(this.game.id);
            }
        },
        computed: {
            userLoggedIn() {
                return Store.getters['user/isAuthenticated'];
            }
        },
        components: {
            'favorite': require('./favorite.vue'),
            'game-thumb-link': require('./game-thumb-link.vue')
        }
    }
</script>


