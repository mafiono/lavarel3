<template>
    <div>
        <category-games v-for="category in categories"
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

        },
        components: {
            'category-games': require('./category-games.vue'),
        },
        mounted() {
            Store.games.fetchCategory().then(x=> this.categories = x);
        }
    }
</script>