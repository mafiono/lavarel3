<template>
    <ul class="game-categories">
        <categories-menu-option :category="null"></categories-menu-option>
        <categories-menu-option v-for="category in orderCategories(categories)" :category="category">
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
        methods: {
            orderCategories: function (list) {
                // Set slice() to avoid to generate an infinite loop!
                return list.slice().sort(function (a, b) {
                    return a.position - b.position;
                });
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
