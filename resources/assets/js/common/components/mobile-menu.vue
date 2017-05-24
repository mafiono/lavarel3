<template>
    <transition name="vue-fade-in">
        <div class="mobile-menu" v-if="show">
            <div class="links">
                <div v-if="userAuthenticated">
                    <div class="link">
                        <a href="#" @click.prevent="toggleSelect('profile')">
                            <i class="cp-user-circle-o"></i><span>Perfil</span><i :class="selectedCss('profile')"></i>
                        </a>
                    </div>
                    <div v-if="profileSelected">
                        <div class="link sub-menu">
                            <a href="/perfil"><span>Info. Pessoal</span><i class="cp-chevron-right"></i></a>
                        </div>
                        <div class="link sub-menu" v-if="userAuthenticated">
                            <a href="/perfil/autenticacao"><span>Autenticação</span><i class="cp-chevron-right"></i></a>
                        </div>
                        <div class="link sub-menu" v-if="userAuthenticated">
                            <a href="/perfil/codigos"><span>Códigos Acesso</span><i class="cp-chevron-right"></i></a>
                        </div>
                    </div>
                </div>

                <div v-if="userAuthenticated">
                    <div class="link">
                        <a href="#" @click.prevent="toggleSelect('bank')">
                            <i class="cp-user-circle-o"></i><span>Banco</span><i :class="selectedCss('bank')"></i>
                        </a>
                    </div>
                    <div v-if="bankSelected">
                        <div class="link sub-menu">
                            <a href="/perfil/banco/saldo"><span>Saldo</span><i class="cp-chevron-right"></i></a>
                        </div>
                        <div class="link sub-menu">
                            <a href="/perfil/banco/depositar"><span>Depositar</span><i class="cp-chevron-right"></i></a>
                        </div>
                        <div class="link sub-menu">
                            <a href="/perfil/banco/conta-pagamentos"><span>Conta Pagamentos</span><i class="cp-chevron-right"></i></a>
                        </div>
                        <div class="link sub-menu">
                            <a href="/perfil/banco/levantar"><span>Levantar</span><i class="cp-chevron-right"></i></a>
                        </div>
                    </div>
                </div>

                <div v-if="userAuthenticated">
                    <div class="link">
                        <a href="#" @click.prevent="toggleSelect('bonus')">
                            <i class="cp-user-circle-o"></i><span>Bónus</span><i :class="selectedCss('bonus')"></i>
                        </a>
                    </div>
                    <div v-if="bonusSelected">
                        <div class="link sub-menu">
                            <a href="/perfil/bonus/porusar"><span>Por Utilizar</span><i class="cp-chevron-right"></i></a>
                        </div>
                        <div class="link sub-menu">
                            <a href="/perfil/bonus/activos"><span>Em Utilização</span><i class="cp-chevron-right"></i></a>
                        </div>
                        <div class="link sub-menu">
                            <a href="/perfil/bonus/utilizados"><span>Utilizados</span><i class="cp-chevron-right"></i></a>
                        </div>
                        <div class="link sub-menu">
                            <a href="/perfil/bonus/amigos"><span>Convidar Amigos</span><i class="cp-chevron-right"></i></a>
                        </div>
                    </div>
                </div>

                <div class="link" v-if="userAuthenticated">
                    <a href="/perfil/historico"><i class="cp-user-circle-o"></i><span>Histórico</span><i class="cp-chevron-right"></i></a>
                </div>

                <div v-if="userAuthenticated">
                    <div class="link">
                        <a href="#" @click="toggleSelect('comunication')">
                            <i class="cp-user-circle-o"></i><span>Comunicação</span><i :class="selectedCss('comunication')"></i>
                        </a>
                    </div>
                    <div v-if="comunicationSelected">
                        <div class="link sub-menu">
                            <a href="/perfil/comunicacao/mensagens"><span>Mensagens</span><i class="cp-chevron-right"></i></a>
                        </div>
                        <div class="link sub-menu" v-if="userAuthenticated">
                            <a href="/perfil/comunicacao/definicoes"><span>Definições</span><i class="cp-chevron-right"></i></a>
                        </div>
                        <div class="link sub-menu" v-if="userAuthenticated">
                            <a href="/perfil/comunicacao/reclamacoes"><span>Reclamações</span><i class="cp-chevron-right"></i></a>
                        </div>
                    </div>
                </div>

                <div v-if="userAuthenticated">
                    <div class="link">
                        <a href="#" @click.prevent="toggleSelect('responsible')">
                            <i class="cp-user-circle-o"></i><span>Jogo Resp.</span><i :class="selectedCss('responsible')"></i>
                        </a>
                    </div>
                    <div v-if="responsibleSelected">
                        <div class="link sub-menu">
                            <a href="/perfil/jogo-responsavel/limites"><span>Limites</span><i class="cp-chevron-right"></i></a>
                        </div>
                        <div class="link sub-menu" v-if="userAuthenticated">
                            <a href="/perfil/jogo-responsavel/autoexclusao"><span>Autenticação</span><i class="cp-chevron-right"></i></a>
                        </div>
                        <div class="link sub-menu" v-if="userAuthenticated">
                            <a href="/perfil/jogo-responsavel/last_logins"><span>Últimos Acessos</span><i class="cp-chevron-right"></i></a>
                        </div>
                    </div>
                </div>

                <div class="br" v-if="userAuthenticated"></div>

                <div class="link" v-if="userAuthenticated">
                    <a href="/perfil/banco/depositar"><i class="cp-user-circle-o"></i><span>Depositar</span><i class="cp-chevron-right"></i></a>
                </div>
                <div class="link" v-if="userAuthenticated">
                    <a href="/perfil/comunicacao/mensagens"><i class="cp-bubbles2"></i><span>Mensagem</span><i class="cp-chevron-right"></i></a>
                </div>

                <div class="br" v-if="userAuthenticated"></div>

                <div class="link">
                    <a href="/favoritos"><i class="cp-star-full"></i>Favoritos<i class="cp-chevron-right"></i></a>
                </div>

                <div class="link">
                    <a href="#" @click.prevent="toggleSelect('support')">
                        <i class="cp-question-circle-o"></i><span>Suporte</span><i :class="selectedCss('support')"></i>
                    </a>
                </div>
                <div v-if="isSelected('support')">
                    <div class="link sub-menu">
                        <a href="/info"><span>Sobre Nós</span><i class="cp-chevron-right"></i></a>
                    </div>
                    <div class="link sub-menu">
                        <a href="/info/termos_e_condicoes"><span>Termos e Condições</span><i class="cp-chevron-right"></i></a>
                    </div>
                    <div class="link sub-menu">
                        <a href="/info/politica_privacidade"><span>Politica de Privacidade</span><i class="cp-chevron-right"></i></a>
                    </div>
                    <div class="link sub-menu">
                        <a href="/info/faq"><span>FAQ</span><i class="cp-chevron-right"></i></a>
                    </div>
                    <div class="link sub-menu">
                        <a href="/info/bonus_e_promocoes"><span>Bónus e Promoções</span><i class="cp-chevron-right"></i></a>
                    </div>
                    <div class="link sub-menu">
                        <a href="/info/pagamentos"><span>Pagamentos</span><i class="cp-chevron-right"></i></a>
                    </div>
                    <div class="link sub-menu">
                        <a href="/info/jogo_responsavel"><span>Jogo Responsável</span><i class="cp-chevron-right"></i></a>
                    </div>
                </div>

                <div class="link" v-if="!userAuthenticated">
                    <a href="javascript:" @click="toggleChat()"><i class="cp-bubbles2"></i>Chat<i class="cp-chevron-right"></i></a>
                </div>

                <div class="br" v-if="userAuthenticated"></div>

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
                selected: "",
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
            },
            toggleSelect(menu) {
                this.selected = this.selected !== menu ? menu : "";
            },
            selectedCss(menu) {
                return this.selected === menu ? "cp-caret-down" : "cp-plus";
            },
            isSelected(menu) {
                return this.selected === menu;
            }
        },
        computed: {
            show() {
                return Store.getters['mobile/getView'] === "menu";
            },
            userAuthenticated() {
                return Store.getters['user/isAuthenticated'];
            },
            profileSelected() {
                return this.selected === "profile";
            },
            bankSelected() {
                return this.selected === "bank";
            },
            bonusSelected() {
                return this.selected === "bonus";
            },
            comunicationSelected() {
                return this.selected === "comunication";
            },
            responsibleSelected() {
                return this.selected === "responsible";
            }
        }
    }
</script>