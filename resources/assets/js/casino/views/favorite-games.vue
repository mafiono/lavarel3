<template>
    <div class="games">
        <error-panel v-if="showError">
            <p>Não existem favoritos.</p>
            <p>Por favor selecione alguns.</p>
        </error-panel>
        <content-games :games="favorites"></content-games>
    </div>
</template>
<script>
    export default {
        data() {
            return {
                favorites: []
            }
        },
        computed: {
            count: function() {
                return this.favorites.length;
            },
            showError: function () {
                return this.count === 0;
            },
        },
        components: {
            'content-games': require('./../components/content-games.vue'),
            'error-panel': require('../../common/components/error-panel.vue')
        },
        watch: {
            // call again the method if the route changes
            '$route': function (to) {
                if (to.path === '/favoritos') {
                    Store.favorites.$active.next('Show View');
                }
            }
        },
        mounted() {
            Store.favorites.store.subscribe(x=> this.favorites = x);
            Store.favorites.$active.next('Show View');
        }
    }
</script>
