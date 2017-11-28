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
                return Store.mobile.isMobile && this.count === 0;
            }
        },
        watch: {
            count: function() {
                if (this.count === 0 && !Store.mobile.isMobile)
                    this.$router.replace('/');
            },
            $route: function(to) {
                if (to.path === '/favorites'
                    && this.count === 0
                    && !Store.mobile.isMobile
                ) {
                    this.$router.replace('/');
                }
            }
        },
        components: {
            'game': require('./../components/game.vue'),
            'error-panel': require('../../common/components/error-panel.vue')
        },
        mounted: function() {
            if (this.count === 0 && !Store.mobile.isMobile)
                this.$router.replace('/');
        }
    }
</script>
