<template>
    <div class="games" v-if="count">
        <div class="header" @click="toggleExpand()" v-if="header">
            <div class="title">
                <i :class="category.class"></i>
                <span class="name">{{category.name}}</span>
            </div>
            <div class="expand" v-if="expandable">
                <span class="count">({{count}})</span>
                <i :class="expandClass"></i>
            </div>
        </div>
        <content-games :games="filteredGames"></content-games>
    </div>
</template>

<script>
    export default {
        data: function () {
            return {
                expanded: false,
                games: [],
            }
        },
        methods: {
            toggleExpand: function () {
                this.expanded = !this.expanded;
            },
            getGames(type) {
                Store.games.getByType(this.type || type)
                    .then(x => this.games = x || []);
            },
        },
        computed: {
            filteredGames: function () {
                return this.expanded || !this.header ? this.games : this.games.slice(0, this.minimizedLimit);
            },
            count: function () {
                return this.games.length;
            },
            expandClass: function () {
                return this.expanded ? "cp-caret-down" : "cp-plus";
            },
            expandable: function () {
                return this.count > this.minimizedLimit;
            },
            minimizedLimit: function () {
                return Store.mobile.isMobile ? 6 : 4;
            },
        },
        mounted() {
            this.getGames();
        },
        watch: {
            category: function (newCat, oldCat) {
                if (newCat !== null) {
                    this.getGames(newCat.categoryId);
                }
            }
        },
        props: {
            category: null,
            type: null,
            header: false,
            gamesLimit: 0
        },
        components: {
            'content-games': require('./content-games.vue')
        }
    }
</script>