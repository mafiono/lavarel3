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
        <img :src="'/assets/portal/img/casino/games/' + game.image" alt="" class="game-img" @click="open">
        <span class="name">{{game.name}}</span>
        <favorite :id="game.id"></favorite>
    </div>
</template>

<script>
    export default{
        props: ['game'],
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
            'favorite': require('./favorite.vue')
        }
    }
</script>


