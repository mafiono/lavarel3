<template>
    <div class="mobile-left-menu-header">
        <ul>
            <li style="width:25%">
                <a href="/" :class="selectedCss('sports')">DESPORTO</a>
            </li>
            <li style="width:25%">
                <a href="/golodeouro" :class="selectedCss('golodeouro')">GOLO D'OURO</a>
            </li>
            <li style="width:25%">
                <router-link to="/">
                    <a href="javascript:;" :class="selectedCss('casino')" @click="OpenCasino()">CASINO</a>
                </router-link>
            </li>
            <li style="width:25%">
                <router-link to="/promocoes">
                    <a href="/promocoes" :class="selectedCss('promocoes')">PROMOÇÕES</a>
                </router-link>
            </li>
        </ul>
    </div>
</template>

<script>
    export default {
        props: ['context'],
        data() {
            return Store.app
        },
        methods: {
            selectedCss: function (link) {
                switch (link) {
                    case 'promocoes': return this.currentRoute === '/promocoes' ? 'selected' : '';
                    case 'golodeouro': return this.currentRoute === '/golodeouro' ? 'selected' : '';
                    case 'casino': return this.context === 'casino' && this.currentRoute !== '/promocoes' ? 'selected' : '';
                    case 'sports':
                    default: return this.currentRoute !== '/promocoes'
                        && this.currentRoute !== '/golodeouro'
                        && this.context !== 'casino' ? 'selected' : '';
                }
            },
            OpenCasino() {
                if (this.context !== 'casino') {
                    window.location = '/casino';
                }
            }
        },
    }
</script>