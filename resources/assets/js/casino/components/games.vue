<template>
    <div>
        <transition mode="out-in">
            <all-games v-show="!category"></all-games>
        </transition>
        <transition mode="out-in">
            <category-games :category="category" type="slot" v-show="isCategory('slot')"></category-games>
        </transition>
        <transition mode="out-in">
            <category-games :category="category" type="cards" v-show="isCategory('cards')"></category-games>
        </transition>
        <transition mode="out-in">
            <category-games :category="category" type="roulette" v-show="isCategory('roulette')"></category-games>
        </transition>
        <transition mode="out-in">
            <category-games :category="category" type="poker" v-show="isCategory('poker')"></category-games>
        </transition>
    </div>
</template>

<script>
    export default {
        methods: {
            isCategory: function(type) {
                return this.category && this.category.id === type;
            }
        },
        computed: {
            category: function() {
                return this.$root.$data.categoriesMenu.selectedCategory;
            }
        },
        components: {
            'category-games': require('./category-games.vue'),
            'all-games': require('./all-games.vue'),
        }
    }
</script>
