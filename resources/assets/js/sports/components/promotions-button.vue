<template>
    <div class="promo-box noselect" @click.prevent="openPromotions()" v-if="show">
        <i class="cp-bookmarks"></i>
        <span> &nbsp; Promoções</span>
        <i :class="iconClass"></i>
    </div>
</template>
<style>
    .promo-box {
        margin: 4px 0 0;
        font-family: 'Exo 2', 'Open Sans', 'Droid Sans', sans-serif;
        font-size: 18px;
        padding: 0 15px;
        line-height: 40px;
        height: 40px;
        background-color: #FF9900;
        color: #fff;
        cursor: pointer;
    }

    .promo-box .casino-bg {
         color: #C69A31;
     }

    .promo-box .cp-plus, .promo-box .cp-caret-right {
        line-height: 40px;
        float: right;
    }
</style>
<script>
    export default{
        data() {
            return {
                iconClass: 'cp-plus'
            }
        },
        methods: {
            openPromotions() {
                if (typeof router !== 'undefined' && router.currentRoute.path === '/promocoes') {
                    page.back('/');
                }

                if (typeof router === 'undefined' && Store.promotions.visible) {
                    page.back('/');
                    return;
                }

                page('/promocoes');
            }
        },
        watch: {
            $route: function(to) {
                this.iconClass = (to.path === '/promocoes') ? 'cp-caret-right' : 'cp-plus';
            },
            promotionsVisibility: function (visible) {
                this.iconClass = visible ? 'cp-caret-right' : 'cp-plus';
            }
        },
        computed: {
            show() {
                return !Store.mobile.isMobile;
            },
            promotionsVisibility() {
                return Store.promotions.visible;
            },
        }
    }
</script>
