<template>
    <div class="box noselect" @click.prevent="openPromotions()">
        <i class="cp-bookmarks"></i>
        <span> &nbsp; Promoções</span>
        <i :class="iconClass"></i>
    </div>
</template>
<style>
    .box {
        font-family: 'Exo 2', 'Open Sans', 'Droid Sans', sans-serif;
        font-size: 18px;
        padding: 0 15px;
        line-height: 40px;
        height: 40px;
        background-color: #D4AD7A;
        color: #fff;
        cursor: pointer;
    }

    .box .cp-plus, .box .cp-caret-right {
        line-height: 39px;
        float: right;
    }
</style>
<script>
    export default{
        methods: {
            openPromotions() {
                if (Store.state.promotions.visible) {
                    page.back();
                    return;
                }
                page('/promocoes');
            }
        },
        computed: {
            iconClass() {
                return Store.state.promotions.visible ? 'cp-caret-right' : 'cp-plus';
            }
        }
    }
</script>
