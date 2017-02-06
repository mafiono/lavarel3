<template>
    <div id="register-container"></div>
</template>
<script>
    export default {
        methods: {
            register: function() {
                var ctx = {
                    params: {
                        0: "/registar",
                        "step": null
                    }
                };
                Register.make(ctx);
            }
        },
        mounted: function() {
            if (userLoggedIn) {
                this.$router.replace('/');

                return;
            }

            this.register();
        }
    }
</script>