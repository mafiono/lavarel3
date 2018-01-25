<template>
    <div class="register" v-if="show">
        <div class="header">
            {{ title }}
            <i id="register-close" class="cp-cross" @click="confirmQuit()"></i>
        </div>
        <register-form></register-form>
    </div>
</template>

<script>
    import RegisterForm from './register-form.vue'

    export default {
        data() {
            return {
                title: "Está a passos de começar a apostar!"
            }
        },
        methods: {
            confirmQuit() {
                $.fn.popup({
                    title: 'ESPERE…',
                    text: 'Complete o seu registo, e não perca a oportunidade de ganhar em grande!',
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Continuar",
                    cancelButtonText: "Cancelar",
                    closeOnConfirm: true,
                    closeOnCancel: true
                }, function (isConfirmed) {
                    if (!isConfirmed) {
                        page('/');
                    }
                })
            }
        },
        computed: {
            show: function () {
                let route = Store.app.currentRoute;
                if (route.indexOf('?') > 0) {
                    route = route.substr(0, route.indexOf('?'));
                }
                return !Store.user.isAuthenticated
                    && (route === '/registar' || route === '/registar/step1');
            }
        },
        components: {
          'register-form': require('./register-form.vue')
        },
        mounted() {
            if (Store.user.isAuthenticated) {
                page('/');
            }
        }
    }
</script>

<style lang="scss" scoped>
    @import '../../../sass/common/variables';

    .register {
        background: #FFF;
        transition: all 1s !important;

        * {
            transition: all 1s !important;
        }

        .header {
            font-family: 'Exo 2', 'Open Sans', 'Droid Sans', sans-serif;
            line-height: 40px;
            vertical-align: middle;
            font-size: 18px;
            height: 40px;

            color: #FFF;
            background-color: #ff9900;
            padding: 0 20px;

            i {
                float: right;
                line-height: 40px;
                margin-left: 15px;
                cursor: pointer;
            }
        }
    }

    @media (max-width: $mobile-screen-width) {
        .register {
            border: 0;
            border-radius: 0;

            .title {
                position: relative;
                line-height: unset;
                min-height: 40px;
                height: auto;
                overflow: hidden;
                padding: 7px 30px 7px 20px;

                i {
                    line-height: unset;
                    position: absolute;
                    top: 15px;
                    right: 15px;
                }
            }
        }
    }
</style>
