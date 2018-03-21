<template>
    <a href="javascript:void(0)" :class="selectedClass" @click="toggle"><i class="cp-heart"></i></a>
</template>

<script>
    export default {
        data: function () {
            return {
                favorites: this.$root.$data.favorites,
            }
        },
        methods: {
            toggle: function() {
                this.favorites[this.id] = !this.favorites[this.id];

                if (!this.userLoggedIn)
                    return;

                if (this.favorites[this.id])
                    this.storeFavorite();
                else
                    this.removeFavorite();
            },
            storeFavorite: function() {
                $.post("/casino/games/favorites", {id: this.id});
            },
            removeFavorite: function() {
                $.post("/casino/games/favorites/" + this.id, {_method: "DELETE"});
            }
        },
        computed: {
            selectedClass: function() {
               return this.favorites[this.id] ? "selected" : "";
            },
            userLoggedIn: function() {
                return Store.user.isAuthenticated;
            }
        },
        props: [
            'id'
        ],
        mounted: function() {
            if (!this.favorites.hasOwnProperty(this.id))
                this.$set(this.favorites, this.id, false);
        }
    }
</script>