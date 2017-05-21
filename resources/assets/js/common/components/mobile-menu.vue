<template>
    <transition name="vue-fade-in">
        <div class="mobile-menu" v-if="show">
            <div class="search">
                <form @submit.prevent="submitSearch">
                    <input placeholder="Procurar" v-model="searchText">
                    <button type="submit"><i class="cp-search"></i></button>
                </form>
            </div>
            <div class="links">
                <div class="link" v-if="userAuthenticated">
                    <a href="/perfil"><i class="cp-user-circle-o"></i><span>Perfil</span><i class="cp-chevron-right"></i></a>
                </div>
                <div class="link">
                    <a href="/info"><i class="cp-question-circle-o"></i><span>Suporte</span><i class="cp-chevron-right"></i></a>
                </div>
                <div class="link">
                    <a href="/favoritos"><i class="cp-star-full"></i>Favoritos<i class="cp-chevron-right"></i></a>
                </div>
                <div class="link">
                    <a href="/promocoes"><i class="cp-notification"></i>Promoções<i class="cp-chevron-right"></i></a>
                </div>
                <div class="link" v-if="userAuthenticated">
                    <a href="/perfil/comunicacao/mensagens"><i class="cp-bubbles2"></i>Mensagens<i class="cp-chevron-right"></i></a>
                </div>
                <div class="link" v-if="!userAuthenticated">
                    <a href="javascript:" @click="toggleChat()"><i class="cp-bubbles2"></i>Chat<i class="cp-chevron-right"></i></a>
                </div>
                <div class="link" v-if="userAuthenticated">
                    <a href="/logout"><i class="cp-sign-out"></i>Logout</a>
                </div>
            </div>
        </div>
    </transition>
</template>

<script>
    export default {
        data() {
            return {
                searchText: ""
            }
        },
        methods: {
            submitSearch() {
                $("#searchForm").children("#textSearch").val(this.searchText).parent().submit();

                this.searchText = "";
            },
            toggleChat() {
                try {
                    Tawk_API.showWidget();

                    Tawk_API.maximize();
                } catch (e) {}
            }
        },
        computed: {
            show() {
                return Store.getters['mobile/getView'] === "menu";
            },
            userAuthenticated() {
                return Store.getters['user/isAuthenticated'];
            }
        }
    }
</script>