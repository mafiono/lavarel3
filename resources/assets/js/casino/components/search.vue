<template>
    <form id="searchForm" @submit.prevent="updateSearch()">
        <input id="textSearch" :value="query" @input="updateSearch"
               type="text" class="botao-registar brand-back" placeholder="Procurar">
    </form>
</template>
<script>
    import Store from '../../common/store/store';
    import {Subject} from 'rxjs/Subject';
    import 'rxjs/add/operator/filter';
    import 'rxjs/add/operator/debounceTime';

    let active$ = new Subject();
    active$.next(true);

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
            this.search$ = new Subject();
            this.search$
                .debounceTime(400)
                .filter(x => x.length > 0)
                .do(x => this.$router.push('/pesquisa/' + x))
                .switchMap(x => Store.games.searchGames(x))
                // .filter(games => games.length || Store.mobile.isMobile)
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
