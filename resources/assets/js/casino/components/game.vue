<template>
    <div class="game">
        <div style="position: relative; z-index:1" v-if="game.isNew === 'True'">
            <span class="tag">Novo</span>
        </div>
        <game-thumb-link :game="game"></game-thumb-link>
        <span class="name">{{game.name}}</span>
        <favorite :id="game.id" :game="game"></favorite>
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
        components: {
            'game-thumb-link': require('./game-thumb-link.vue'),
            'favorite': require('./favorite.vue')
        }
    }
</script>


