<template>
    <form id="searchForm" @submit.prevent="updateSearch()">
        <input id="textSearch" :value="query" @input="updateSearch"
               type="text" class="botao-registar brand-back" placeholder="Procurar">
    </form>
</template>
<script>
    import Store from '../../common/store/store';
    import { filter, debounceTime, switchMap, tap, } from 'rxjs/operators';

    export default {
        data: function() {
            return this.$root.$data.search;
        },
        methods: {
            updateSearch: function (event) {
                this.query = event.target.value;
                this.search$.next(this.query);
            }
        },
        mounted: function() {
            this.search$ = Store.games.$search;
            this.search$
                .pipe(
                    debounceTime(400),
                    filter(x => x.length > 0),
                    tap(x => this.$router.push('/pesquisa/' + x)),
                    switchMap(x => Store.games.searchGames(x))
                    // filter(games => games.length || Store.mobile.isMobile)
                )
                .subscribe(games => this.games = games);

            if (this.$root.$route.path.includes('/pesquisa')
                && Object.keys(this.$root.$route.params).length
                && this.$root.$route.params.term) {
                this.query = this.$root.$route.params.term;
                this.search$.next(this.query);
            }
        }
    }
</script>
