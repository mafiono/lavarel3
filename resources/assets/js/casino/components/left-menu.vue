<template>
    <div class="left-menu">
        <promotions-button></promotions-button>
        <categories-menu></categories-menu>
    </div>
</template>

<script>
    export default {
        components: {
            'categories-menu': require('./categories-menu.vue'),
            'promotions-button': require('../../sports/components/promotions-button.vue')
        }
    }
</script>
