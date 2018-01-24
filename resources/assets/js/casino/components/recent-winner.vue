<template>
    <div class="recent-winner">
        <game-thumb-link :game="winner" width="108px" height="61px"></game-thumb-link>
        <div class="details">

            <span class="username">{{ winner.username }}</span>
            <span class="amount">{{ winner.amount }}</span>
            <game-link :game="winner"></game-link>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'recent-winner',
        components: {
            'game': require('./game.vue'),
            'game-thumb-link': require('./game-thumb-link.vue'),
            'game-link': require('./game-link.vue')
        },
        props: ['winner']
    }
</script>

<style lang="scss" scoped>
    .recent-winner {
        display: block;
        overflow: hidden;
        padding-bottom: 15px;

        .details {
            float: right;
            text-align: right;

            .username, .amount, .game-link {
                display: block;
                text-overflow: ellipsis;
                white-space: nowrap;
                overflow: hidden;
                font-size: 14px;
                font-family: "Open Sans", "Droid Sans", Verdana, sans-serif;
                color: #99ccff;
                width: 85px;
            }

            .game-link {
                text-decoration: underline;
                margin-right: 16px;
                cursor: pointer;
            }
        }
    }
</style>
