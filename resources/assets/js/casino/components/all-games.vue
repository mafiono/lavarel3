<template>
    <div>
        <category-games v-for="category in orderCategories(categories)"
                        :category="category"
                        :type="category.categoryId"
                        games-limit="4" header="true"></category-games>
    </div>
</template>

<script>
    export default {
        data: function () {
            return {
                categories: []
            };
        },
        methods: {
            orderCategories: function (list) {
                // Set slice() to avoid to generate an infinite loop!
                return list.slice().sort(function (a, b) {
                    return a.position - b.position;
                });
            }
        },
        components: {
            'category-games': require('./category-games.vue'),
        },
        mounted() {
            Store.games.fetchCategory().then(x=> this.categories = x);
        }
    }
</script>