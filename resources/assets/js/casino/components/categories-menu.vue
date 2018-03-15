<template>
    <ul class="game-categories">
        <categories-menu-option :category="null"></categories-menu-option>
        <categories-menu-option v-for="category in categories" :category="category">
        </categories-menu-option>
    </ul>
</template>
<script>
    export default {
        data: function () {
            return {
                categories: []
            }
        },
        mounted() {
            Store.games.fetchCategory().then(x => this.categories = x);
        },
        components: {
            'categories-menu-option': require('./categories-menu-option.vue')
        }
    }

</script>
