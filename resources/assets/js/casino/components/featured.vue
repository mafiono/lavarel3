<template>
    <div class="featured" v-show="show">
        <game v-for="game in featured" :game=game></game>
    </div>
</template>
<script>
    export default{
        data: function() {
            return {
                routes: ['/', '/favorites', '/pesquisa'],
                show: false
            }
        },
        computed: {
            featured: function() {
                return this.$root.$data.games.filter(game => game.featured === 1);
            },
            count: function() {
                return this.featured.length;
            }
        },
        watch: {
            $route: function(to) {
                this.show = !this.routes.includes('/' + to.path.split('/')[1]);
            }
        },
        mounted: function() {
           this.show = !this.routes.includes(this.$route.path);
        },
        components:{
            'game' : require('./../components/game.vue')
        }
    }
</script>
