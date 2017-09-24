<template>
    <div class="balance-button">
        <router-link to="/perfil/banco/depositar">
            <a href="/perfil/banco/depositar" class="balance">
                {{ balanceAmount | currency | mask(masked) }}
            </a>
        </router-link>
        <button class="mask" @click="toggle()">
            <i title="Mostrar" class="cp-eye" v-if="masked"></i>
            <i title="Ocultar" class="cp-eye-blocked" v-else></i>
        </button>
    </div>
</template>

<script>
    export default {
        data: function () {
            return {
                masked: null
            };
        },
        methods: {
            toggle: function () {
                this.masked = !this.masked;
                localStorage.setItem('balance-masked', this.masked);
            }
        },
        filters: {
            currency: function (value) {
                return number_format(value, 2, '.', ',') + ' EUR';
            },
            mask: function (value, masked) {
                return masked ? "####" : value;
            }
        },
        props: ['balanceAmount'],
        mounted() {
            this.masked = !! localStorage.getItem('balance-masked');
        }
    }
</script>