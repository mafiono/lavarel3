<template>
    <mobile-login context="casino"></mobile-login>
</template>
<script>
    export default {
        components: {
            'mobile-login': require('../../common/components/mobile-login.vue'),
        },
        mounted() {
            if(Store.user.isAuthenticated)
                router.replace('/');
        }
    }
</script>
