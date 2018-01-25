<template>
    <form id="searchForm" @submit.prevent="updateSearch()">
        <input id="textSearch" v-model="query" type="text" class="botao-registar brand-back" placeholder="Procurar">
    </form>
</template>
<script>
    import Store from '../../common/store/store';

    export default {
        data: function() {
            return this.$root.$data.search;
        },
        methods: {
            updateSearch: function() {
                if (this.query.length < 1) return;

                games = this.$root.$data.games.filter(
                    game => game.name.toLowerCase().includes(this.query.toLowerCase())
                        && (game.mobile === (isMobile.any*1) || game.desktop === ((!isMobile.any)*1))
                );

                if (games.length > 0 || Store.mobile.isMobile) {
                    this.games = games;
                    this.$router.push('/pesquisa/' + this.query);
                }
            }
        },
        watch: {
            query: function() {
                this.updateSearch();
            }
        },
        mounted: function() {
            if (this.$root.$route.path.includes('/pesquisa')
                && Object.keys(this.$root.$route.params).length
                && this.$root.$route.params.term)
                this.query = this.$root.$route.params.term;
        }
    }
</script>
