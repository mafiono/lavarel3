<template>
    <div class="recent-winner">
        <game :game="winner" :hide-description="true"></game>
        <div class="footer">
            <span class="username">{{ winner.username }}</span>
            <span class="amount">{{ winner.amount }}</span>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'recent-winner',
        components: {
            'game': require('./game.vue')
        },
        props: ['winner']
    }
</script>

<style lang="scss" scoped>
    .recent-winner {
        display: block;

        .game {
            margin: 6px;
        }

        .footer {
            position: relative;
            overflow: hidden;
            padding: 0 10px;
            top: -5px;

            .username, .amount {
                display: inline-block;
                text-overflow: ellipsis;
                white-space: nowrap;
                overflow: hidden;
                font-size: 14px;
                font-family: "Open Sans", "Droid Sans", Verdana, sans-serif;
                color: #99ccff;
            }

            .username {
                width: 150px;
                text-align: left;
            }

            .amount {
                float: right;
            }
        }
    }
</style>
