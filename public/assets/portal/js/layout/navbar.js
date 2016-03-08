/**
 * Created by miguel on 08/03/2016.
 */
(function ($) {
    var scroll$ = Rx.Observable.fromEvent(window, 'scroll')
        .map(function(e){
            return window.pageYOffset;
        });

    scroll$.subscribe(function(x){
        console.log('scroll',x);
    });

})(jQuery);