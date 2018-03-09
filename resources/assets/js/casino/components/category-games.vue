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
        <game v-for="game in filteredGames" :game="game"></game>
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
            if (this.type) {
                Store.games.getByType(this.type)
                    .then(x => this.games = x);
            }
        },
        watch: {
            category: function (newCat, oldCat) {
                if (newCat !== null) {
                    Store.games.getByType(this.type || newCat.categoryId)
                        .then(x => this.games = x);
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
            'game': require('./game.vue')
        }
    }
</script>