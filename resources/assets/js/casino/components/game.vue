<template>
    <div class="game">
        <div style="position: relative; z-index:1" v-show="game.is_new">
            <span class="tag">Novo</span>
        </div>
        <game-thumb-link :game="game" width="217px" height="126px"></game-thumb-link>
        <span class="name">{{game.name}}</span>
        <favorite :id="game.id"></favorite>
    </div>
</template>

<script>
    export default{
        props: ['game'],
        methods: {
            open: function() {
                router.push(`/game-lobby/${this.game.id}`);
            },
        },
        computed: {
            userLoggedIn() {
                return Store.user.isAuthenticated;
            }
        },
        components: {
            'game-thumb-link': require('./game-thumb-link.vue'),
            'favorite': require('./favorite.vue')
        }
    }
</script>


