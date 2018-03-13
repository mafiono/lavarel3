<template>
    <div class="featured" v-show="show">
        <game v-for="game in featured" :game=game></game>
    </div>
</template>
<script>
    export default{
        data: function() {
            return {
                routes: ['/', '/favoritos', '/pesquisa', '/promocoes', '/rondas-abertas'],
                featured: [],
                show:false
            }
        },
        computed: {
            count: function() {
                return this.featured.length;
            },
            show() {
                return this.featured.length;
            },
        },
        watch: {
            $route: function(to) {
                this.show = !this.routes.includes('/' + to.path.split('/')[1]);
            }
        },
        components:{
            'game' : require('./../components/game.vue')
        },
        
        methods:{
            featuredGames: function() {
               Store.games.getFeaturedGames()
               .then(x => this.featured = x);
            }
        },
        mounted:
            function() {
                this.show = !this.routes.includes(this.$route.path);
                this.featuredGames();
            },
    }
</script>
