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
                canShow: false
,            }
        },
        computed: {
            count() {
                return this.featured.length;
            },
            show() {
                return this.canShow && this.count;
            },
        },
        watch: {
            $route: function(to) {
                this.canShow = !this.routes.includes('/' + to.path.split('/')[1]);
            },
            canShow: function (to) {
                if (to) {
                    this.featuredGames();
                }
            }
        },
        components:{
            'game' : require('./../components/game.vue')
        },
        methods:{
            featuredGames: function () {
                if (this.featured.length) return;
                Store.games.getFeaturedGames()
                    .then(x => this.featured = x);
            }
        },
        mounted() {
            this.canShow = !this.routes.includes(this.$route.path);
        },
    }
</script>
