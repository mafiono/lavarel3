<template>
    <transition name="vue-fade">
        <div class="cookies-consent" v-if="show">
            <div class="overlay">
                <div class="content">
                    <div class="text">
                        <span>Este site utiliza cookies para uma experiência mais amigável e segura, ao navegar estará a consentir a sua utilização.
                            <br>
                            <router-link to="/info/politica_privacidade">
                                <a href="/info/politica_privacidade">Política de Privacidade e Cookies.</a>
                            </router-link>
                        </span>
                    </div>
                    <button class="btn btn-success" @click="accept()">OK</button>
                </div>
            </div>
        </div>
    </transition>
</template>

<script>
    import Cookies from 'cookies-js';

    export default {
        name: 'cookies-consent',
        data() {
            return {
                show: false
            }
        },
        methods: {
            accept: function () {
                Cookies.set('cookieconsent', 'allow', {expires: 9999});
                this.show = false;
            }
        },
        mounted() {
            this.show = Cookies.get('cookieconsent') !== 'allow';
        }
    }
</script>
