/**
 * Created by miguel on 08/03/2016.
 */
$(function ($) {
    var btn = document.getElementById('btnLogin');
    var btnLogin$ = Rx.Observable.fromEvent(btn, 'click');
    btnLogin$.subscribe(function (x) {
        btn.style.display = 'none';
        var formLogin = document.getElementById('saveLoginForm');
        formLogin.style.display = 'block';
    });
    
    
    var scroll$ = Rx.Observable.fromEvent(window, 'scroll')
        .map(function(e){
            return window.pageYOffset;
        });

    scroll$.subscribe(function(x){
        console.log('scroll',x);
    });

});