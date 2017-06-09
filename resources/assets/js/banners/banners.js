import {Observable} from 'rxjs/Observable';
import 'rxjs/add/observable/fromPromise';
import 'rxjs/add/operator/do';
import 'rxjs/add/operator/mergeMap';
import 'rxjs/add/operator/retry';
import 'rxjs/add/operator/publishReplay';

Handlebars.registerPartial('banner.title', require('./banner_title.html'));
Handlebars.registerPartial('banner.carousel', require('./banner_carousel.html'));

window.BannersMenu = new function () {
    let dataTypes = {};
    let banners = Observable.fromPromise($.get('/api/banners', {}).promise())
        .retry(3)
        .publishReplay(1)
        .refCount()
        ;

    this.make = function ({container, types}) {
        container.empty();
        banners
            .map(function (x) {
                x.map(function (x) {
                    dataTypes[x.type] = x;
                    return x;
                });
                return x;
            })
            .subscribe(function (z) {
                    types.map(function (item){
                        if (dataTypes[item]) {
                            container.append(Template.apply("banner." + item, dataTypes[item]));
                        }
                    });
                }, function (err) {
                    console.error(err);
                }, function () {
                    console.log('Finish');
                }
            );
    }
};