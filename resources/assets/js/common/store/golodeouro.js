import { Observable } from 'rxjs/Observable';
import { Subject } from "rxjs/Subject";
import { combineLatest } from 'rxjs/observable/combineLatest';
import { first, filter, map, switchMap, shareReplay } from 'rxjs/operators';

function callHttp(url) {
    return Observable.fromPromise($.getJSON(url)).pipe(map(x => x.data));
}
export default {
    $show: new Subject(),
    $feed: null,
    active: {},
    details: {},
    image: null,
    visible: false,
    getGolo() {
        return callHttp('/api/active');
    },
    getCompetitors(id){
        return callHttp(`/api/selections/${id}/Marcador`);
    },
    getResults(id){
        return callHttp(`/api/selections/${id}/Resultado final`);
    },
    getTimes(id){
        return callHttp(`/api/selections/${id}/Minuto Primeiro Golo`);
    },
    getAmounts(id){
        return callHttp(`/api/${id}/values`);
    },
    getInactives(){
        return callHttp(`/api/lastactive`);
    },
    setActive(golo) {
        this.active = golo;
        if (golo !== null && golo.details)
        {
            this.details = golo.details = JSON.parse(golo.details);
            this.image = this.details.image || null;
        }
        return golo;
    },
    getAllForGolo(golo) {
        // let id = this.active.id;
        return combineLatest(
            this.getCompetitors(golo.id),
            this.getAmounts(golo.id),
            this.getResults(golo.id),
            this.getTimes(golo.id),
            this.getInactives(),
            (competitors, amounts, results, times, inactives) => {
                golo.competitors = competitors;
                golo.amounts = amounts;
                golo.results = results;
                golo.times = times;
                golo.inactives = inactives;
                return golo;
            }
        );
    },
    getFeed() {
        if (this.$feed === null) {
            this.$feed = this.$show
                .pipe(
                    filter(x => x),
                    first(),
                    switchMap(x => this.getGolo()),
                    switchMap(x => this.getAllForGolo(x)),
                    map(x => this.setActive(x)),
                    shareReplay(1)
                );
        }
        return this.$feed;
    },
}