<template>
    <div>
        <promotions></promotions>
    </div>
</template>

<script>
    export default {
        components: {
            'promotions': require('../../common/components/promotions.vue')
        },
        mounted() {
            Store.commit('promotions/setVisible', true);
        }
    }
</script>