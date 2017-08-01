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
                windowWidth: 0,
                expanded: false
            }
        },
        methods: {
            toggleExpand: function() {
                this.expanded = !this.expanded;
            }
        },
        computed: {
            games: function() {
                return this.$root.$data.games
                    .filter(game => this.type === game.type_id && game.mobile === (isMobile.any*1));
            },
            filteredGames: function() {
                return this.expanded ? this.games : this.games.slice(0, this.minimizedLimit);
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
                return this.windowWidth < 767 ? 6 : 4;
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
            this.windowWidth= $(window).width();

            $(window).resize(() => {
                this.windowWidth = $(window).width();
            });
        }
    }
</script>