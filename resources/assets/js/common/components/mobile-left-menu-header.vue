<template>
    <div class="mobile-left-menu-header">
        <ul>
            <li style="width:25%">
                <a href="/" :class="selectedCss('sports')" @click="defineContext('sports')">DESPORTO</a>
            </li>
            <li style="width:25%">
                <a href="/golodeouro" :class="selectedCss('golodeouro')" @click="defineContext('golodeouro')">GOLO D'OURO</a>
            </li>
            <li style="width:25%">
                <router-link to="/">
                    <a href="javascript:;" :class="selectedCss('casino')" @click="OpenCasino();defineContext('casino')">CASINO</a>
                </router-link>
            </li>
            <li style="width:25%">
                <router-link to="/promocoes">
                    <a href="/promocoes" :class="selectedCss('promotions')" @click="defineContext('promotions')">PROMOÇÕES</a>
                </router-link>
            </li>
        </ul>
    </div>
</template>

<script>
    export default {
        props: ['context'],
        data(){
            return {local: this.context}
        },
        methods: {
            selectedCss: function (link) {
                var context;
                if (this.local === 'casino') {
                    context = 'casino';
                } else if (this.local === 'golodeouro') {
                    context = 'golodeouro';
                } else if (this.local === 'promotions'){
                    context = 'promotions';
                } else {
                    context = 'sports';
                }
                return context === link ? 'selected' : '';
            },
            OpenCasino() {
                if (this.context !== 'casino') {
                    window.location = '/casino';
                }
            },
            defineContext(value){
                return this.local = value;
            }
        },
    }
</script>