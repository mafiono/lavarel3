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
                            <router-link to="/perfil">
                                <a href="/perfil"><span>Informação Pessoal</span><i class="cp-chevron-right"></i></a>
                            </router-link>
                        </div>
                        <div class="link sub-menu" v-if="userAuthenticated">
                            <router-link to="/perfil/autenticacao">
                                <a href="/perfil/autenticacao"><span>Documentos</span><i class="cp-chevron-right"></i></a>
                            </router-link>
                        </div>
                        <div class="link sub-menu" v-if="userAuthenticated">
                            <router-link to="/perfil/codigos">
                                <a href="/perfil/codigos"><span>Códigos Acesso</span><i class="cp-chevron-right"></i></a>
                            </router-link>
                        </div>
                    </div>
                </div>

                <div v-if="userAuthenticated">
                    <div class="link">
                        <a href="#" @click.prevent="toggleSelect('bank')">
                            <i class="cp-pig"></i><span>Banco</span><i :class="selectedCss('bank')"></i>
                        </a>
                    </div>
                    <div v-if="bankSelected">
                        <div class="link sub-menu">
                            <router-link to="/perfil/banco/saldo">
                                <a href="/perfil/banco/saldo"><span>Saldo</span><i class="cp-chevron-right"></i></a>
                            </router-link>
                        </div>
                        <div class="link sub-menu">
                            <router-link to="/perfil/banco/depositar">
                                <a href="/perfil/banco/depositar"><span>Depositar</span><i class="cp-chevron-right"></i></a>
                            </router-link>
                        </div>
                        <div class="link sub-menu">
                            <router-link to="/perfil/banco/conta-pagamentos">
                                <a href="/perfil/banco/conta-pagamentos"><span>Conta Pagamentos</span><i class="cp-chevron-right"></i></a>
                            </router-link>
                        </div>
                        <div class="link sub-menu">
                            <router-link to="/perfil/banco/levantar">
                                <a href="/perfil/banco/levantar"><span>Levantar</span><i class="cp-chevron-right"></i></a>
                            </router-link>
                        </div>
                    </div>
                </div>

                <div class="link" v-if="userAuthenticated">
                    <router-link to="/perfil/historico">
                        <a href="/perfil/historico"><i class="cp-history"></i><span>Histórico</span><i class="cp-chevron-right"></i></a>
                    </router-link>
                </div>

                <div v-if="userAuthenticated">
                    <div class="link">
                        <a href="#" @click.prevent="toggleSelect('comunication')">
                            <i class="cp-megaphone"></i><span>Comunicação</span><i :class="selectedCss('comunication')"></i>
                        </a>
                    </div>
                    <div v-if="comunicationSelected">
                        <div class="link sub-menu">
                            <router-link to="/perfil/comunicacao/mensagens">
                                <a href="/perfil/comunicacao/mensagens"><span>Mensagens</span><i class="cp-chevron-right"></i></a>
                            </router-link>
                        </div>
                        <div class="link sub-menu" v-if="userAuthenticated">
                            <router-link to="/perfil/comunicacao/definicoes">
                                <a href="/perfil/comunicacao/definicoes"><span>Definições</span><i class="cp-chevron-right"></i></a>
                            </router-link>
                        </div>
                        <div class="link sub-menu" v-if="userAuthenticated">
                            <router-link to="/perfil/comunicacao/reclamacoes">
                                <a href="/perfil/comunicacao/reclamacoes"><span>Reclamações</span><i class="cp-chevron-right"></i></a>
                            </router-link>
                        </div>
                    </div>
                </div>

                <div v-if="userAuthenticated">
                    <div class="link">
                        <a href="#" @click.prevent="toggleSelect('responsible')">
                            <i class="cp-verified_user"></i><span>Jogo Responsável</span><i :class="selectedCss('responsible')"></i>
                        </a>
                    </div>
                    <div v-if="responsibleSelected">
                        <div class="link sub-menu">
                            <router-link to="/perfil/jogo-responsavel/limites">
                                <a href="/perfil/jogo-responsavel/limites"><span>Limites</span><i class="cp-chevron-right"></i></a>
                            </router-link>
                        </div>
                        <div class="link sub-menu" v-if="userAuthenticated">
                            <router-link to="/perfil/jogo-responsavel/autoexclusao">
                                <a href="/perfil/jogo-responsavel/autoexclusao"><span>Autoexclusão</span><i class="cp-chevron-right"></i></a>
                            </router-link>
                        </div>
                        <div class="link sub-menu" v-if="userAuthenticated">
                            <router-link to="/perfil/jogo-responsavel/last_logins">
                                <a href="/perfil/jogo-responsavel/last_logins"><span>Últimos Acessos</span><i class="cp-chevron-right"></i></a>
                            </router-link>
                        </div>
                    </div>
                </div>

                <div class="br" v-if="userAuthenticated"></div>

                <div class="link" v-if="userAuthenticated">
                    <router-link to="/perfil/banco/depositar">
                        <a href="/perfil/banco/depositar"><i class="cp-pig-coin"></i><span>Depositar</span><i class="cp-chevron-right"></i></a>
                    </router-link>
                </div>
                <div class="link" v-if="userAuthenticated">
                    <router-link to="/perfil/comunicacao/mensagens">
                        <a href="/perfil/comunicacao/mensagens"><i class="cp-bubbles2"></i><span>Mensagem {{unreads}}</span><i class="cp-chevron-right"></i></a>
                    </router-link>
                </div>

                <div class="br" v-if="userAuthenticated"></div>

                <div class="link">
                    <router-link to="/favorites">
                        <a href="/favoritos"><i :class="favoritesIcon"></i>Favoritos<i class="cp-chevron-right"></i></a>
                    </router-link>
                </div>

                <div class="link">
                    <a href="#" @click.prevent="toggleSelect('support')">
                        <i class="cp-question-circle-o"></i><span>Suporte</span><i :class="selectedCss('support')"></i>
                    </a>
                </div>
                <div v-if="isSelected('support')">
                    <div class="link sub-menu">
                        <router-link to="/info/contactos">
                            <a href="/info/contactos"><span>Sobre Nós</span><i class="cp-chevron-right"></i></a>
                        </router-link>
                    </div>
                    <div class="link sub-menu">
                        <router-link to="/info/pagamentos">
                            <a href="/info/pagamentos"><span>Pagamentos</span><i class="cp-chevron-right"></i></a>
                        </router-link>
                    </div>
                    <div class="link sub-menu">
                        <a href="https://afiliados.casinoportugal.pt"><span>Afiliados</span><i class="cp-chevron-right"></i></a>
                    </div>
                    <div class="link sub-menu">
                        <router-link to="/info/termos_e_condicoes">
                            <a href="/info/termos_e_condicoes"><span>Termos e Condições</span><i class="cp-chevron-right"></i></a>
                        </router-link>
                    </div>
                    <div class="link sub-menu">
                        <router-link to="/info/politica_privacidade">
                            <a href="/info/politica_privacidade"><span>Politica de Privacidade</span><i class="cp-chevron-right"></i></a>
                        </router-link>
                    </div>
                    <div class="link sub-menu">
                        <router-link to="/info/jogo_responsavel">
                            <a href="/info/jogo_responsavel"><span>Jogo Responsável</span><i class="cp-chevron-right"></i></a>
                        </router-link>
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
            }
        },
        methods: {
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
                return Store.mobile.view === "menu";
            },
            userAuthenticated() {
                return Store.user.isAuthenticated;
            },
            profileSelected() {
                return this.selected === "profile";
            },
            bankSelected() {
                return this.selected === "bank";
            },
            comunicationSelected() {
                return this.selected === "comunication";
            },
            responsibleSelected() {
                return this.selected === "responsible";
            },
            unreads() {
                return Store.user.unreads ? "(" + Store.user.unreads + ")": "";
            },
            favoritesIcon() {
                return this.context === 'casino' ? 'cp-heart' : 'cp-star-full';
            }
        },
        props: ['context']
    }
</script>