<template>
    <div class="games">
        <game v-for="game in favorites" :game=game></game>
        <error-panel v-if="showError">
            <p>Não existem favoritos.</p>
            <p>Por favor selecione alguns.</p>
        </error-panel>
    </div>
</template>
<script>
    export default {
        computed: {
            favorites: function() {
                return this.$root.$data.games.filter(game => this.$root.$data.favorites[game.id]);
            },
            count: function() {
                return this.favorites.length;
            },
            showError: function () {
                return this.count === 0;
            }
        },
        components: {
            'game': require('./../components/game.vue'),
            'error-panel': require('../../common/components/error-panel.vue')
        }
    }
</script>
