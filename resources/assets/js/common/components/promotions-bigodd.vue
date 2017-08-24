<template>
    <table border="0" cellpadding="1" cellspacing="1" class="bigodd" v-if="tops.length">
        <tr class="row">
            <td colspan="4">&nbsp;</td>
        </tr>
        <tr class="title">
            <td></td>
            <td>Utilizador</td>
            <td>Odd</td>
            <td>Montante</td>
        </tr>
        <tr class="row" v-for="top in tops">
            <td>{{top.pos}} º</td>
            <td>{{top.username | name}}</td>
            <td>{{top.odd}}</td>
            <td>{{top.amount | currency}} </td>
        </tr>
        <tr class="row">
            <td colspan="4">&nbsp;</td>
        </tr>
    </table>
    <p v-else>Não existem apostas.</p>
</template>
<script>
    export default {
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
        props: ['previous'],
        mounted() {
            let previous = (typeof this.previous !== "undefined") ? "?previous" : "";

            $.get("/promotions/bigodd" + previous, (data) => {
                this.tops = data;
            });
        }
    }
</script>