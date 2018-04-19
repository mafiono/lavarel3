<template>
    <a href="javascript:void(0)" :class="selectedClass" @click="toggle"><i class="cp-heart"></i></a>
</template>

<script>
    export default {
        data: function () {
            return {
                favorites: []
            }
        },
        methods: {
            toggle: function() {
                if (!this.userLoggedIn)
                    return;

                if (!Store.favorites.isInList(this.id))
                    this.storeFavorite();
                else
                    this.removeFavorite();
            },
            storeFavorite: function() {
                Store.favorites.postStore(this.game);
            },
            removeFavorite: function() {
                Store.favorites.postDelete(this.game);
            }
        },
        computed: {
            selectedClass: function() {
               return Store.favorites.isInList(this.id) ? "selected" : "";
            },
            userLoggedIn: function() {
                return Store.user.isAuthenticated;
            }
        },
        props: [
            'id',
            'game',
        ],
    }
</script>