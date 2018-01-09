<template>
    <transition name="vue-fade-in">
        <div class="promotions" v-if="visible">
            <promotions-list type="sports"></promotions-list>
        </div>
    </transition>
</template>
<script>
    export default {
        components: {
            'promotions-list': require('./promotions-list.vue')
        },
        computed: {
            visible() {
                return Store.promotions.visible;
            },
            loaded() {
                return Store.promotions.loaded;
            }
        },
        mounted() {
            Store.promotions.fetch();
        }
    }
</script>
