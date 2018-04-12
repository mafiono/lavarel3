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
        <div class="content-games">
            <game v-for="game in filteredGames" :game="game"></game>
        </div>
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
                switch (this.type) {
                    case 'jackpot':
                        return game.jackpot === 1;
                    case 'slot':
                        return game.jackpot === 0 && this.type === game.type_id;
                }

                return this.type === game.type_id
            },
            gameEnable(game) {
                if (isMobile.any*1) {
                    return game.mobile;
                }
                return game.desktop;
            }
        },
        computed: {
            games: function() {
                return this.$root.$data.games
                    .filter(game => this.gameFilter(game) && this.gameEnable(game));
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
                return Store.mobile.isMobile ? 6 : 4;
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

<style lang="scss" scoped>
    @import '../../../sass/common/variables';

    .content-games {
        display: -webkit-box;
        display: -moz-box;
        display: -ms-flexbox;
        display: -webkit-flex;
        display: flex;

        flex-flow: row wrap;
        justify-content: flex-start;
    }
    .game {
        width: 25%;
        padding: 5px;

        @media (max-width: $mobile-screen-width) {
            width: 50%;
        }
    }
</style>