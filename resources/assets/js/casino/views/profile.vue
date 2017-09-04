<template>
    <div id="perfil-container">
    </div>
</template>
<script>
    export default{
        data: function () {
            return this.$root.$data.profile;
        },
        methods: {
            renderProfile: function (path) {
                if (!this.routes[path]) {
                    this.$router.push('/');

                    return;
                }

                var params = JSON.parse(JSON.stringify(this.routes[path]));

                if (path === '/perfil/historico') {
                    PerfilHistory.make(params)
                }   else {
                    Perfil.make(params);
                }
            }
        },
        watch: {
            $route: function (to, from) {
                let path = to.path;

                if (path.substring(0, 7) === "/perfil") {
                    this.renderProfile(to.path);
                }
            }
        },
        mounted: function() {
            this.renderProfile(this.$router.currentRoute.path);
        }
    }
</script>
