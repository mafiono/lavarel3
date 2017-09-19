<template>
    <transition name="vue-fade-in">
        <div class="mobile-header">
            <div class="header-content">
                <div class="clock">
                    <div class="timers">
                        <div class="server-time" :data-time="serverTime"></div>
                        <div class="user-time hide" :data-time="userLoginTime">
                            <i class="cp-clock"></i> <span></span>
                        </div>
                    </div>
                </div>
                <div class="navbar">
                    <a href="#" class="left-menu" @click.prevent="closeSportsMenu()" v-if="isViewingLeftMenu">
                        <i class="cp-chevron-left"></i>
                    </a>
                    <router-link to="/mobile/menu-casino" v-else>
                        <a href="/mobile/menu-desportos" class="left-menu">
                            <i class="cp-menu"></i>
                        </a>
                    </router-link>
                    <div class="logo">
                        <router-link to="/">
                            <a rel="home" href="/" title="Casino Portugal" class="navbar-brand nav-onscroll" style="display: inline;">
                                <img alt="CasinoPortugal" src="/assets/portal/img/Logo-CP.svg">
                            </a>
                        </router-link>
                    </div>
                    <div class="menu" @click="toggleMenu()">
                        <i :class="menuIconClass"></i>
                    </div>
                    <div class="login" v-if="!userAuthenticated">
                        <router-link to="/mobile/login">
                            <a href="/mobile/login" class="login-btn" :class="casinoClass">Registo/ Login</a>
                        </router-link>
                    </div>
                    <div class="user-info" v-if="userAuthenticated">
                        <p>{{username}}</p>
                        <p>{{userBalance | currency}}</p>
                    </div>
                </div>
            </div>
            <div class="header-block"></div>
        </div>
    </transition>
</template>

<script>
    export default {
        methods: {
            toggleMenu() {
                if (this.isViewingMenu)
                    page.back('/');
                else
                    page('/mobile/menu');
            },
            closeSportsMenu() {
                page.back("/");
            }
        },
        computed: {
            userAuthenticated() {
                return Store.getters['user/isAuthenticated'];
            },
            userBalance() {
                return Store.getters['user/getTotalBalance'];
            },
            username() {
                return Store.getters['user/getUsername'];
            },
            isViewingLeftMenu() {
                return Store.getters['mobile/getView'] === "menu-desportos"
                    || Store.getters['mobile/getView'] === "menu-casino";
            },
            isViewingMenu() {
                return Store.getters['mobile/getView'] === "menu";
            },
            menuIconClass() {
                return this.isViewingMenu ? "cp-cross" : "cp-dots-three-vertical";
            },
            casinoClass() {
                return this.casinoContext ? 'casino-bg' : '';
            },
            casinoContext() {
                return this.context === 'casino';
            },

        },
        props: [
            "userLoginTime",
            "serverTime",
            "context"
        ],
        filters: {
            currency(value) {
                return value !== "" ? value + " €": "";
            }
        },
    }
</script>