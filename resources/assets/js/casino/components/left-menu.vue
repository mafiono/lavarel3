<template>
    <div class="left-menu">
        <favorites-button context="casino"></favorites-button>
        <categories-menu></categories-menu>
        <open-rounds-button></open-rounds-button>
    </div>
</template>

<script>
    export default {
        components: {
            'categories-menu': require('./categories-menu.vue'),
            'favorites-button': require('../../common/components/favorites-button.vue'),
            'open-rounds-button': require('./open-rounds-button.vue')
        }
    }
</script>
