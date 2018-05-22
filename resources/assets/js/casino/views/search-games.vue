<template>
    <div>
        <div class="games">
            <content-games :games="games"></content-games>
        </div>
        <error-panel v-if="showError">
            <p>Não existem resultados.</p>
            <p>Por favor altere a sua pesquisa.</p>
        </error-panel>
    </div>
</template>
<script>
    export default {
        data: function() {
            return this.$root.$data.search
        },
        components: {
            'game' : require('./../components/game.vue'),
            'error-panel': require('../../common/components/error-panel.vue'),
            'content-games': require('./../components/content-games.vue'),
        },
        computed: {
            count: function() {
                return this.games.length;
            },
            showError: function() {
                return this.count === 0;
            }
        }
    }
</script>
