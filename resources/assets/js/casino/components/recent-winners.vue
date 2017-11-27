<template>
    <transition name="vue-fade">
        <div class="recent-winners" v-if="winners.length">
            <h2 class="header"><i class="cp-bookmarks"></i> &nbsp; Ganhos Recentes</h2>
            <div class="content">
                <div class="winners-wrapper">
                    <div class="winners">
                        <recent-winner v-for="winner in winners" :winner="winner"></recent-winner>
                    </div>
                </div>
            </div>
        </div>
    </transition>
</template>

<script>
    export default {
        data() {
            return {
                winners: []
            }
        },
        methods: {
            scrollDown: function (n, pos) {
                $('.recent-winners .winners').css({
                    top: `-${pos * 76}px`,
                    transition: `top ease ${pos ? 2 : 0}s`
                });

                window.setTimeout(() => {
                    this.scrollDown(n, (pos + 1) % n);
                }, 2000 + (pos ? 2000 : 50));
            },
        },
        components: {
            'recent-winner': require('./recent-winner.vue')
        },
        mounted() {
            $.get('/casino/recent-winners')
                .done((data) => {
                    data.concat(data.slice(0,4)).forEach((winner) => {
                       this.winners.push(winner);
                    });

                    let n = data.length;

                    if (n > 4) {
                        window.setTimeout(() => this.scrollDown(n + 1, 0), 2000);
                    }
                });
        }
    }
</script>

<style lang="scss" scoped>
    @import '../../../sass/global/variables';
    @media (max-width: $mobile-screen-width) {
        .recent-winners {
            display: none;
        }
    }

    .recent-winners {
        margin-top: 5px;
        background: #1e293e;

        .header {
            padding: 0 15px;
            font-size: 18px;
            font-family: "Exo 2", "Open Sans", "Droid Sans", sans-serif;
            font-weight: normal;
            background: #2d415c;
            line-height: 44px;
            color: white;
            margin: 0;

            i {
                font-size: 20px;
                line-height: 44px;
                margin-right: 2px;
            }
        }

        .content {
            padding: 15px;

            .winners-wrapper {
                height: 304px;
                overflow: hidden;

                .winners {
                    width: 217px;
                    position: relative;
                    transition: top ease 1s;
                }
            }
        }
    }
</style>
