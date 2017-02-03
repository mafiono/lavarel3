<template>
    <iframe :src="src" style="height: 681px; width: 640px; border: 0; float: left"
        scrolling="no" @load="go($event.target.contentWindow.location.pathname)">
    </iframe>
</template>
<script>
    export default{
        data: function () {
            return this.$root.$data.profile;
        },
        methods: {
            go: function(path) {
                this.iframePath = path;
                this.$router.replace(path);
            }
        },
        watch: {
            $route: function (to, from) {
                if (this.routes.includes(to.path) && this.iframePath !== to.path)
                    this.src = this.src === to.path ? to.path + " " : to.path;
            }
        },
        mounted: function() {
            this.src = this.$route.path;
        }
    }
</script>
