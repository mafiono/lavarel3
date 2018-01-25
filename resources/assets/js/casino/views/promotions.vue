<template>
    <div>
        <promotions type="casino"></promotions>
    </div>
</template>

<script>
    export default {
        components: {
            'promotions': require('../../common/components/promotions.vue')
        },
        mounted() {
            Store.promotions.visible = true;
        }
    }
</script>