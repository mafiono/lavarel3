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
        data: function() {
            return {
                expanded: false
            }
        },
        methods: {
            toggleExpand: function() {
                this.expanded = !this.expanded;
            },
            gameFilter(game) {
                return this.type === 'jackpot'
                    ? game.jackpot === 1
                    : this.type === game.type_id;
            }
        },
        computed: {
            games: function() {
                return this.$root.$data.games
                    .filter(game => this.gameFilter(game) && game.mobile === (isMobile.any*1));
            },
            filteredGames: function() {
                return this.expanded || !this.header ? this.games : this.games.slice(0, this.minimizedLimit);
            },
            count: function() {
                return this.games.length;
            },
            expandClass: function() {
                return this.expanded ? "cp-caret-down" :  "cp-plus";
            },
            expandable: function() {
                return this.count > this.minimizedLimit;
            },
            minimizedLimit: function () {
                return Store.getters['mobile/getIsMobile'] ? 6 : 4;
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
        }
    }
</script>