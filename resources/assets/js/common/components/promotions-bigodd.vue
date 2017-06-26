<template>
    <table border="0" cellpadding="1" cellspacing="1" class="bigodd">
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
            <td>{{top.username}}</td>
            <td>{{top.odd}}</td>
            <td>{{top.amount | currency}} </td>
        </tr>
        <tr class="row">
            <td colspan="4">&nbsp;</td>
        </tr>

    </table>
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
            }
        },
        props: ['month'],
        mounted() {
            $.get("/promotions/bigodd" + this.month+"?month=", (data) => {
                this.tops = data;
            });
        }
    }
</script>