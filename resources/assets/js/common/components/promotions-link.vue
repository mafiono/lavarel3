<template>
    <span class="promotions-link noselect" @click="openPromotions()" title="PROMOÇÕES" :class="selectedClass">PROMOÇÕES</span>
</template>

<script>
    export default{
        methods: {
            openPromotions() {
                page('/promocoes');

                this.$nextTick(this.removeActiveFromOtherMenuItems);
            },

            removeActiveFromOtherMenuItems() {
                $('.header-prematch, .header-casino').removeClass('active');
            }
        },
        computed: {
            selectedClass() {
                return this.isPromotionsVisible
                    ? 'selected'
                    : '';
            },
            isPromotionsVisible() {
                return Store.app.currentRoute === '/promocoes' || Store.app.currentRoute === '/casino/promocoes';
            },
        },
        mounted() {
            if (this.isPromotionsVisible) {
                this.removeActiveFromOtherMenuItems();
            }
        }
    }
</script>

<style lang="scss">
    .promotions-link {
        display: inline-block;
        font-family: 'Exo 2', 'Open Sans', 'Droid Sans', sans-serif;
        color: #fff;
        font-size: 16px;
        font-weight: bold;
        cursor: pointer;
        padding: 10px 15px;

        &:hover, &.selected {
            color: #FF9900;
        }
    }

    .bs-wp .navbar-default.navbar-2nd li .promotions-link {
        padding: 5px 8px !important;
    }
</style>
