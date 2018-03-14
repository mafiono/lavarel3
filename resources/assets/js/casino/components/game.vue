<template>
    <div class="game">
        <div style="position: relative; z-index:1" v-show="game.is_new">
            <span class="tag">Novo</span>
        </div>
        <img :src="'/assets/portal/img/casino/games/' + game.image" alt="" style="max-width:217px;" class="game-img"
             @click="open">
        <span class="name">{{game.name}}</span>
        <favorite :id="game.id"></favorite>
    </div>
</template>

<script>
    export default {
        props: ['game'],
        methods: {
            open: function () {
                router.push(`/game-lobby/${this.game.id}`);
            },
        },
        computed: {
            userLoggedIn() {
                return Store.user.isAuthenticated;
            }
        },
        components: {
            'favorite': require('./favorite.vue')
        }
    }
</script>


