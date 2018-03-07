<template>
    <li v-if="category" class="game-category" :class="selectedClass" @click="select()">
        <i v-bind:class="category.cssIcon"></i>
        <span>{{category.name}}</span>
        <i class="cp-plus"></i>
        <i class="cp-caret-right"></i>
    </li>
    <li v-else="category" class="game-category" :class="selectedClass" @click="select()">
        <i class="cp-baccarat"></i>
        <span>Todos</span>
        <i class="cp-plus"></i>
        <i class="cp-caret-right"></i>
    </li>
</template>
<script>
    export default {
        data: function () {
            return this.$root.$data.categoriesMenu;
        },
        methods: {
            select: function () {
                this.selectedCategory = this.category;
                this.$router.push("/");
            },
        },
        computed: {
            selectedClass: function () {
                return (this.selectedCategory === this.category && this.$route.path === "/") ? "selected" : "";
            }
        },
        props: [
            'category'
        ],
    }
</script>
