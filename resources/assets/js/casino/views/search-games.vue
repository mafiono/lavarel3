<template>
    <div>
        <div class="games">
            <game v-for="game in games" :game=game></game>
        </div>
        <error-panel v-if="showError">
            <p>Não existem resultados.</p>
            <p>Por favor selecione alguns.</p>
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
            'error-panel': require('../../common/components/error-panel.vue')
        },
        computed: {
            count: function() {
                return this.games.length;
            },
            showError: function() {
                return Store.getters['mobile/getIsMobile'] && this.count === 0;
            }
        },
        watch: {
            count: function() {
                if (this.count === 0 && !Store.getters['mobile/getIsMobile'])
                    this.$router.replace('/');
            }
        },
        mounted: function() {
            if (this.count === 0 && !Store.getters['mobile/getIsMobile'])
                this.$router.replace('/');
        }
    }
</script>
