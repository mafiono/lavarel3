<template>
    <ul class="game-categories">
        <categories-menu-option :category="null"></categories-menu-option>
        <categories-menu-option v-for="category in categories" :category="category"></categories-menu-option>
    </ul>
</template>
<script>
    export default{
        computed: {
            categories: function() {
                return this.$root.$data.categories.filter(
                    category => (this.$root.$data.games
                        .filter(game => category.id === game.type_id)
                        .length > 0) || category.id === 'jackpot'
                );
            }
        },
        components: {
            'categories-menu-option': require('./categories-menu-option.vue')
        }
    }
</script>
