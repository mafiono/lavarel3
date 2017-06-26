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

    <!--<table border="0" cellpadding="1" cellspacing="1" style="width: 600px;">-->
        <!--<tbody>-->
        <!--<tr>-->
            <!--<td colspan="5" align="right" bgcolor="#2D415C"><span class="white">&nbsp;</span><span class="white">&nbsp;</span><span class="white">&nbsp;</span><span class="white">&nbsp;</span><span class="white">&nbsp;</span></td>-->
        <!--</tr>-->
        <!--<tr>-->
            <!--<td width="49" align="center" bgcolor="#2D415C">&nbsp;</td>-->
            <!--<td width="193" align="left" bgcolor="#2D415C" class="titulo">Utilizador</td>-->
            <!--<td width="163" align="center" bgcolor="#2D415C" class="titulo">Odd</td>-->
            <!--<td width="159" align="right" bgcolor="#2D415C" class="titulo">Montante</td>-->
            <!--<td width="20" rowspan="4" align="right" bgcolor="#2D415C"><span class="white">&nbsp;</span><span class="white">&nbsp;</span><span class="white">&nbsp;</span><span class="white">&nbsp;</span></td>-->
        <!--</tr>-->
        <!--<tr>-->
            <!--<td align="center" bgcolor="#2D415C"><span class="yellow">1º</span></td>-->
            <!--<td align="left" bgcolor="#2D415C"><span class="white">Marauder</span></td>-->
            <!--<td align="center" bgcolor="#2D415C"><span class="white">20.50</span></td>-->
            <!--<td align="right" bgcolor="#2D415C"><span class="white">500€</span></td>-->
        <!--</tr>-->
        <!--<tr>-->
            <!--<td align="center" bgcolor="#2D415C"><span class="yellow">2º</span></td>-->
            <!--<td align="left" bgcolor="#2D415C"><span class="white">Jaquim</span></td>-->
            <!--<td align="center" bgcolor="#2D415C"><span class="white">19.23</span></td>-->
            <!--<td align="right" bgcolor="#2D415C"><span class="white">450€</span></td>-->
        <!--</tr>-->
        <!--<tr>-->
            <!--<td align="center" bgcolor="#2D415C"><span class="yellow">3º</span></td>-->
            <!--<td align="left" bgcolor="#2D415C"><span class="white">Toino</span></td>-->
            <!--<td align="center" bgcolor="#2D415C"><span class="white">9.45</span></td>-->
            <!--<td align="right" bgcolor="#2D415C"><span class="white">345€</span></td>-->
        <!--</tr>-->
        <!--<tr>-->
            <!--<td colspan="5" align="right" bgcolor="#2D415C"><span class="white">&nbsp;</span><span class="white">&nbsp;</span><span class="white">&nbsp;</span><span class="white">&nbsp;</span><span class="white">&nbsp;</span></td>-->
        <!--</tr>-->
        <!--</tbody>-->
    <!--</table>-->
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
        mounted() {
            $.get("/promotions/bigodd", (data) => {
                this.tops = data;
            });
        }
    }
</script>