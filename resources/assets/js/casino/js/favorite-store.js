import {Subject} from 'rxjs/Subject';

import {timer} from 'rxjs/observable/timer';
import {fromPromise} from 'rxjs/observable/fromPromise';
import {of} from 'rxjs/observable/of';
import {startWith, map, filter, switchMap, tap, shareReplay, mergeMap } from 'rxjs/operators';

export default {
    $listFavorites: null,
    listFull: [],
    listFavoritesActive: {},
    $active: new Subject(),

    get store() {
        if (this.$listFavorites === null) {
            this.$listFavorites = this.requestFavorite()
                .pipe(
                    tap(x => this.setList(x)),
                    shareReplay(1),
                    mergeMap(x => this.$active
                        .pipe(startWith(1))
                        .map(() => x)),
                    map(() => this.getFull())
                );
        }
        return this.$listFavorites;
    },
    isInList(id) {
        let inList = this.listFavoritesActive[id] || false;
        return inList;
    },
    setList(list) {
        this.listFavoritesActive = {};
        list.forEach(x => {
            this.listFull.push(x);
            this.listFavoritesActive[x.id] = true;
        });
    },
    getFull() {
        return this.listFull;
    },
    postStore(game){
        this.listFavoritesActive[game.id] = true;
        this.listFull.push(game);
        this.requestPostFavorite(game.id)
            .subscribe(x => {
                this.$active.next('updated');
            });
    },
    postDelete(game){
        this.listFavoritesActive[game.id] = false;
        this.listFull = this.listFull.filter(i => i.id !== game.id);
        this.requestDeleteFavorite(game.id)
            .subscribe(x => {
                this.$active.next('removed');
            });
    },
    requestFavorite() {
        return fromPromise($.get('/casino/games/favorites').promise());
    },
    requestPostFavorite($id){
        return fromPromise($.post('/casino/games/favorites', {id:$id}).promise());
    },
    requestDeleteFavorite($id){
        return fromPromise($.ajax({
            url: '/casino/games/favorites/' + $id,
            type: 'DELETE'
        }).promise());
    }
}