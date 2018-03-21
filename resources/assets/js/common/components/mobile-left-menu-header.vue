<template>
    <div class="mobile-left-menu-header">
        <ul>
            <li style="width:25%">
                <a href="/" :class="selectedCss('sports')">DESPORTO</a>
            </li>
            <li style="width:25%">
                <a href="/golodeouro" :class="selectedCss('sports')">GOLO D'OURO</a>
            </li>
            <li style="width:25%">
                <router-link to="/">
                    <a href="javascript:;" :class="selectedCss('casino')" @click="OpenCasino()">CASINO</a>
                </router-link>
            </li>
            <li style="width:25%">
                <router-link to="/promocoes">
                    <a href="/promocoes">PROMOÇÕES</a>
                </router-link>
            </li>
        </ul>
    </div>
</template>

<script>
    export default {
        methods: {
            selectedCss: function (link) {
                return (this.context === 'casino' ? 'casino' : 'sports') === link
                    ? 'selected' : '';
            },
            OpenCasino() {
                if (this.context !== 'casino') {
                    window.location = '/casino';
                }
            }
        },
        props: ['context']
    }
</script>