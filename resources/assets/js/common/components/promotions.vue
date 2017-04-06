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
                return Store.state.promotions.visible;
            },
            loaded() {
                return Store.state.promotions.loaded;
            }
        },
        watch: {
            visible: function(newVisibility) {
                if (newVisibility && !this.loaded) {
                    Store.dispatch('promotions/fetch');
                }
            }
        }
    }
</script>
