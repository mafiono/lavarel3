<template>
    <div class="mobile-left-menu-header">
        <ul>
            <li>
                <a href="/" :class="selectedCss('sports')">DESPORTO</a>
            </li>
            <li>
                <router-link to="/">
                    <a href="/casino" :class="selectedCss('casino')">CASINO</a>
                </router-link>
            </li>
            <li>
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
            }
        },
        props: ['context']
    }
</script>