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
                    <a href="/mobile/menu-desportos" class="sports" v-if="!isViewingSportsMenu">
                        <i class="cp-menu"></i>
                    </a>
                    <a href="#" class="sports" @click.prevent="closeSportsMenu()" v-if="isViewingSportsMenu">
                        <i class="cp-chevron-left"></i>
                    </a>
                    <div class="logo">
                        <a rel="home" href="/" title="Casino Portugal" class="navbar-brand nav-onscroll" style="display: inline;">
                            <img alt="CasinoPortugal" src="/assets/portal/img/Logo-CP.svg">
                        </a>
                    </div>
                    <div class="menu" @click="toggleMenu()">
                        <i :class="menuIconClass"></i>
                    </div>
                    <div class="login" v-if="!userAuthenticated">
                        <a href="/mobile/login" class="login-btn">Registo/ Login</a>
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
                    page.back();
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
                return Store.getters['user/getBalance'];
            },
            username() {
                return Store.getters['user/getUsername'];
            },
            isViewingSportsMenu() {
                return Store.getters['mobile/getView'] === "menu-desportos";
            },
            isViewingMenu() {
                return Store.getters['mobile/getView'] === "menu";
            },
            menuIconClass() {
                return this.isViewingMenu ? "cp-cross" : "cp-dots-three-vertical";
            }
        },
        props: [
            "userLoginTime",
            "serverTime"
        ],
        filters: {
            currency(value) {
                return value !== "" ? value + " €": "";
            }
        }
    }
</script>