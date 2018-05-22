<template>
    <div class="open-rounds">
        <div class="games" v-if="hasGames">
            <content-games :games="games"></content-games>
        </div>
        <error-panel v-else>
            <p>Não existem jogos com rondas abertas.</p>
        </error-panel>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                games: [],
                hasGames: true,
            }
        },
        components: {
            'game' : require('./../components/game.vue'),
            'error-panel': require('../../common/components/error-panel.vue'),
            'content-games': require('./../components/content-games.vue'),
        },
        mounted() {
            if (!Store.user.isAuthenticated) {
                this.$router.push('/');
            }

            $.get('/casino/open-rounds')
                .done((games) => this.games = games)
                .always((games) => this.hasGames = games.length > 0)
        }
    }
</script>

<style lang="scss" scoped>
    .open-rounds {
        .games {
            min-height: 184px;
        }
    }
</style>