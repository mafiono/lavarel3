<template>
    <div class="games" v-if="count">
        <div class="header" @click="toggleExpand()" v-if="header">
            <div class="title">
                <i class="fa" :class="category.class"></i>
                <span class="name">{{category.name}}</span>
            </div>
            <div class="expand" v-if="expandable">
                <span class="count">({{count}})</span>
                <i class="fa" :class="expandClass"></i>
            </div>
        </div>
        <game v-for="game in filteredGames" :game="game"></game>
    </div>
</template>

<script>
    export default {
        data: function() {
            return {
                limit: 0
            }
        },
        methods: {
            toggleExpand: function() {
                this.limit = !this.limit ? 4 : 0;
            }
        },
        computed: {
            games: function() {
                return this.$root.$data.games.filter(game => this.type === game.type_id);
            },
            filteredGames: function() {
                return this.limit ? this.games.slice(0, this.limit) : this.games;
            },
            count: function() {
                return this.games.length;
            },
            expandClass: function() {
                return this.limit ? "cp-plus" : "cp-caret-down";
            },
            expandable: function() {
                return this.count > 4;
            },
        },
        props: {
            category: null,
            type: null,
            header: false,
            gamesLimit: 0
        },
        components: {
            'game': require('./game.vue')
        },
        mounted: function() {
            this.limit = this.gamesLimit;
        }
    }
</script>