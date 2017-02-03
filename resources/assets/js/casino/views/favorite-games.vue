<template>
    <div class="games">
        <game v-for="game in favorites" :game=game></game>
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
            }
        },
        watch: {
            count: function() {
                if (this.count === 0)
                    this.$router.replace('/');
            },
            $route: function(to) {
                if (to.path === '/favorites' && this.count === 0)
                this.$router.replace('/');
            }
        },
        components: {
            'game' : require('./../components/game.vue')
        },
        mounted: function() {
            if (this.count === 0)
                this.$router.replace('/');
        }
    }
</script>
