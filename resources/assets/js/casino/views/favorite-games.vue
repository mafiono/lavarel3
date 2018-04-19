<template>
    <div class="games">
        <game v-for="game in favorites" :game=game></game>
        <error-panel v-if="showError">
            <p>Não existem favoritos.</p>
            <p>Por favor selecione alguns.</p>
        </error-panel>
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
            'game': require('./../components/game.vue'),
            'error-panel': require('../../common/components/error-panel.vue')
        },
        watch: {
            // call again the method if the route changes
            '$route': function (to) {
                if (to.path === '/favoritos') {
                    console.log('I am in view');
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
