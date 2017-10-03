<template>
    <table border="0" cellpadding="1" cellspacing="1" class="promotions-endurance" v-if="tops.length">
        <tr class="row">
            <td colspan="4">&nbsp;</td>
        </tr>
        <tr class="title">
            <td></td>
            <td>Utilizador</td>
            <td>Dias consecutivos</td>
            <td>Montante</td>
        </tr>
        <tr class="row" v-for="(top, index) in tops">
            <td>{{ index + 1 }} º</td>
            <td>{{ top.username | name }}</td>
            <td>{{ top.days }}</td>
            <td>{{ top.amount | currency }} </td>
        </tr>
        <tr class="row">
            <td colspan="4">&nbsp;</td>
        </tr>
    </table>
    <p v-else>Não existem utilizadores.</p></template>

<script>
    export default {
        name: 'component-name',
        data() {
            return {
                tops: []
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
        props: ['game', 'start-date', 'interval'],
        mounted() {
            $.get(`/promotions/endurance?game=${this.game}`
                + `&start-date=${this.startDate}&interval=${this.interval}`, (data) => {
                this.tops = data;
            });
        }
    }
</script>
