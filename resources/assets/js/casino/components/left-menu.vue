<template>
    <div class="left-menu">
        <promotions-button></promotions-button>
        <categories-menu></categories-menu>
        <recent-winners></recent-winners>
    </div>
</template>

<script>
    export default {
        components: {
            'categories-menu': require('./categories-menu.vue'),
            'promotions-button': require('../../sports/components/promotions-button.vue'),
            'recent-winners': require('./recent-winners.vue'),
        }
    }
</script>
