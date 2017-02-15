<template>
    <div class="games">
        <game v-for="game in games" :game=game></game>
    </div>
</template>
<script>
    export default {
        data: function() {
            return this.$root.$data.search
        },
        components: {
            'game' : require('./../components/game.vue')
        },
        computed: {
            count: function() {
                return this.games.length;
            }
        },
        watch: {
            count: function() {
                if (this.count === 0)
                    this.$router.replace('/');
            }
        },
        mounted: function() {
            if (this.count === 0)
                this.$router.replace('/');
        }
    }
</script>
