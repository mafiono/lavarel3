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
                    <div class="sports" @click="showSportsMenu()" v-if="!viewingSportsMenu">
                        <i class="cp-menu"></i>
                    </div>
                    <div class="sports" @click="closeSportsMenu()" v-if="viewingSportsMenu">
                        <i class="cp-chevron-left"></i>
                    </div>
                    <div class="logo">
                        <a rel="home" href="/" title="Casino Portugal" class="navbar-brand nav-onscroll" style="display: inline;">
                            <img alt="CasinoPortugal" src="/assets/portal/img/Logo-CP.svg">
                        </a>
                    </div>
                    <div class="menu" @click="toggleMenu()">
                        <i :class="menuIconClass"></i>
                    </div>
                    <div class="login" v-if="!userAuthenticated">
                        <button class="login-btn" @click="showMobileLogin()">Registo/ Login</button>
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
            showSportsMenu() {
                MobileHelper.showSportsMenu()
            },
            showRegister() {
                MobileHelper.showPage('/registar');
            },
            showMobileLogin(){
                MobileHelper.showMobileLogin();
            },
            toggleMenu(){
                this.viewingMenu ? MobileHelper.showContent() : MobileHelper.showMobileMenu();
            },
            closeSportsMenu() {
                MobileHelper.showContent();
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
            viewingSportsMenu() {
                return Store.getters['mobile/getView'] === "sportsMenu";
            },
            viewingMenu() {
                return Store.getters['mobile/getView'] === "menu";
            },
            menuIconClass() {
                return this.viewingMenu ? "cp-cross" : "cp-dots-three-vertical";
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