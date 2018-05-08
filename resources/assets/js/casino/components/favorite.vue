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
            },
            checkClass: function(){
                this.className = Store.favorites.isInList(this.id) ? "selected" : "";
            }
        },
        watch: {
            '$route'(to, from) {
                this.checkClass();
            },
        },

        computed: {
            userLoggedIn: function() {
                return Store.user.isAuthenticated;
            },
        },

        beforeUpdate(){
            this.checkClass();
        },
        props: [
            'id',
            'game',
        ],
    }
</script>