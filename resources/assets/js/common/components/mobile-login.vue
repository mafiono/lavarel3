<template>
    <transition name="vue-fade-in">
        <div class="mobile-login" v-if="show">
            <div class="header"><i class="cp-user-circle"></i>LOGIN <i class="cp-cross" @click="quit()"></i></div>
            <div class="content">
                <form @submit.prevent="submitLogin()">
                    <div class="row">
                        <input name="username" v-model="username" placeholder="Introduza o nome do utilizador"/>
                    </div>
                    <div class="row">
                        <input name="password" v-model="password" type="password" placeholder="Introduza a palavra-Passe"/>
                    </div>
                    <div class="row">
                        <button type="submit" class="login-btn">LOGIN</button>
                    </div>
                </form>
                <div class="row">
                    <div class="reset-password">
                        <a href="#" title="Recuperar dados" @click.prevent="resetPassword()">Recuperar dados</a>
                    </div>
                </div>
                <div class="row">
                    <button class="register-btn" @click="register">REGISTAR</button>
                </div>
            </div>
        </div>
    </transition>
</template>

<script>
    export default {
        data() {
            return {
                username: '',
                password: '',
            }
        },
        methods: {
            submitLogin() {
                $("#user-login").val(this.username);
                $("#pass-login").val(this.password);
                $("#submit-login").click();
            },
            resetPassword() {
                $("#btn_reset_pass").click();
            },
            register() {
                page('/registar');
            },
            quit() {
                page.back();
            }
        },
        computed: {
            show() {
                return Store.getters['mobile/getView'] === "login" && !Store.getters['user/isAuthenticated'];
            }
        },
    }
</script>