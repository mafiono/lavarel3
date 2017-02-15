<template>
    <a href="#" class="btn btn-clean fright"  :class="updateClass" :style="activeStyle" title="Ver Favoritos" @click.prevent="showFavorites()"><i class="fa fa-heart"></i></a>

</template>
<script>
    export default {
        data: function() {
            return {
                favorites: this.$root.$data.favorites,
                updateClass: ''
            }
        },
        methods: {
            showFavorites: function() {
                if (this.hasFavorites)
                    this.$router.push("/favorites");
            }
        },
        computed: {
            activeStyle: function() {
                return this.hasFavorites ? {color: "#C69A31"} : {};
            },
            hasFavorites: function() {
                for (var i in this.favorites)
                    if (this.favorites[i] === true)
                        return true;
                return false;
            },
            count: function() {
                return Object.values(this.favorites).filter(value => value).length;
            }
        },
        watch: {
            count: function() {
                this.updateClass = 'heartbeat';
                setTimeout(function () {
                    this.updateClass = '';
                }.bind(this), 1000);
            }
        }
    }
</script>