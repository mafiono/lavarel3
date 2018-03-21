<template>
    <div>
        <mobile-left-menu-header context="casino"></mobile-left-menu-header>
        <mobile-search-bar context="casino"></mobile-search-bar>
        <left-menu></left-menu>
    </div>
</template>

<script>
    export default {
        components: {
            'mobile-left-menu-header': require('../../common/components/mobile-left-menu-header.vue'),
            'mobile-search-bar': require('../../common/components/mobile-search-bar.vue'),
            'left-menu': require('../components/left-menu.vue')
        }
    }
</script>