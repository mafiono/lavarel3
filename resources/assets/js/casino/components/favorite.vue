<template>
    <a href="javascript:void(0)" :class="className" @click="toggle"><i class="cp-heart"></i></a>
</template>

<script>
    export default {
        data: function () {
            return {
                favorites: [],
                className: '',
            }
        },
        methods: {
            toggle: function() {
                if (!Store.favorites.isInList(this.id)) {
                    this.storeFavorite();
                    this.className = "selected";
                }
                else {
                    this.removeFavorite();
                    this.className = "";
                }
            },
            storeFavorite: function() {
                Store.favorites.postStore(this.game);
            },
            removeFavorite: function() {
                Store.favorites.postDelete(this.game);
            }
        },

        watch: {
            '$route'(to, from) {
                this.className = Store.favorites.isInList(this.id) ? "selected" : "";
            },
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
        mounted() {
            this.className = Store.favorites.isInList(this.id)? "selected" : "";

        }
    }
</script>