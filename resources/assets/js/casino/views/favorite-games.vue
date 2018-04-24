<template>
    <div class="games">
        <content-games :games="favorites"></content-games>
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
            'content-games': require('./../components/content-games.vue'),
            'error-panel': require('../../common/components/error-panel.vue')
        }
    }
</script>
