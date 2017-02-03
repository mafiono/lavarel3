<template>
    <iframe src="/registar/step1" scrolling="no"
        style="height: 1000px; width: 640px; border: 0; float: left;">
    </iframe>
</template>
<script>
    export default {
        mounted: function() {
            if (userLoggedIn)
                this.$router.replace('/');
        }
    }
</script>