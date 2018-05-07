<template>
    <div>
        <transition  mode="out-in" name="vue-fade">
            <all-games v-show="!category"></all-games>
        </transition>
        <transition  mode="out-in" name="vue-fade">
            <category-games v-show="category" :category="category"></category-games>
        </transition>
    </div>
</template>

<script>
    export default {
        data: function () {
            return this.$root.$data.categoriesMenu;
        },
        methods: {
            isCategory: function (type) {
                return this.category && this.category.id === type;
            }
        },
        computed: {
            category: function () {
                var current = this.$root.$data.categoriesMenu.selectedCategory;
                return current;
            }
        },
        components: {
            'category-games': require('./category-games.vue'),
            'all-games': require('./all-games.vue'),
        },
    }
</script>
