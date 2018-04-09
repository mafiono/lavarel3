<template>
    <table border="0" cellpadding="1" cellspacing="1" class="promotions-endurance" v-if="previous === undefined && tops.length">
        <tr>
            <td></td>
            <td class="username">{{ getUsername(0) | name }} <br> {{ getAmount(0) | currency }}</td>
            <td></td>
        </tr>
        <tr>
            <td class="username">
                <span v-if="tops.length > 1">{{ getUsername(1) | name }} <br> {{ getAmount(1) | currency }}</span>
            </td>
            <td class="podium"><span class="rank">1</span></td>
            <td class="username">
                <span v-if="tops.length > 2">{{ getUsername(2) | name }} <br> {{ getAmount(2) | currency }}</span>
            </td>
        </tr>
        <tr>
            <td class="podium"><span class="rank">2</span></td>
            <td class="podium"><img class="podium-logo" src="/assets/portal/img/Logo-CP.svg" alt="CASINO PORTUGAL"/></td>
            <td class="podium"><span  class="rank">3</span></td>
        </tr>
    </table>
    <span class="promotions-endurance previous" v-else-if="previous !== undefined && tops.length">{{ getUsername(0) | name }}</span>
    <p v-else>Não existem apostas.</p>
</template>

<script>
    export default {
        name: 'component-name',
        data() {
            return {
                tops: []
            }
        },
        methods: {
            getUsername (position) {
                if (position < this.tops.length) {
                    return this.tops[position].username;
                }

                return '';
            },
            getAmount (position) {
                if (position < this.tops.length) {
                    return this.tops[position].amount;
                }

                return '';
            }
        },
        filters: {
            currency: function (value) {
                return `${number_format(value, 2, '.', ' ')}€`;
            },
            name: function (value) {
                return "***" + value.substring(3, value.length);
            }
        },
        props: ['game', 'start-date', 'interval', 'previous'],
        mounted() {
            $.get(`/promotions/endurance?game=${this.game}`
                + `&start-date=${this.startDate}&interval=${this.interval}`, (data) => {
                this.tops = data;
            });
        }
    }
</script>

<style lang="scss" scoped>
    @import '../../../sass/common/mixins';

    .promotions-endurance {
        width: 100%;
        @include noselect();

        tr {
            td {
                width: 33%;
                height: 60px;
                text-align: center;

                &.username {
                    color: #2D415C;
                    font-family: "Open Sans", "Droid Sans", Verdana, sans-serif;
                    font-weight: bold;
                    font-size: 16px;
                }

                &.podium {
                    background: #2D415C;

                    .rank {
                        font-family: "Exo 2", "Open Sans", "Droid Sans", sans-serif;
                        font-weight: bold;
                        font-size: 42px;
                        color: #C69A31;
                    }
                }

                .podium-logo {
                    max-width: 100px;
                    width: 100%;
                }
            }
        }

        &.previous {
            font-family: "Source Sans Pro", "Helvetica Neue", Helvetica, Arial, sans-serif;
            font-style: italic;
            color: #000;
        }
    }
</style>
