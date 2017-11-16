<template>
    <div class="favorites-button noselect" @click.prevent="openFavorites()" v-if="show">
        <i :class="contextIconClass"></i>
        <span> &nbsp; Favoritos</span>
        <i :class="expandIconClass"></i>
    </div>
</template>

<script>
    export default{
        methods: {
            openFavorites() {
                if (this.isFavoritesVisible) {
                    page.back('/');
                } else {
                    page('/favoritos');
                }
            }
        },
        computed: {
            show() {
                return !Store.mobile.isMobile;
            },
            isFavoritesVisible() {
                return Store.app.currentRoute === '/favoritos'
                    || Store.app.currentRoute === '/casino/favoritos';
            },
            contextIconClass() {
                return this.context === 'casino' ? 'cp-heart' : 'cp-star-full';
            },
            expandIconClass() {
                return this.isFavoritesVisible ? 'cp-caret-right' : 'cp-plus';
            }
        },
        props: ['context']
    }
</script>

<style lang="scss">
    .favorites-button {
        margin: 4px 0 0;
        font-family: 'Exo 2', 'Open Sans', 'Droid Sans', sans-serif;
        font-size: 18px;
        padding: 0 15px;
        line-height: 40px;
        height: 40px;
        background-color: #FF9900;
        color: #fff;
        cursor: pointer;

        .casino-bg {
            color: #C69A31;
        }

        .cp-heart, .cp-star-full {
            font-size: 16px;
        }

        .cp-plus, .cp-caret-right {
            line-height: 40px;
            float: right;
        }
    }
</style>
