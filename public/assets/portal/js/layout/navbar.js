/**
 * Created by miguel on 08/03/2016.
 */
$(function ($) {
    var btn = document.getElementById('btnLogin');
    if (btn != null) {
        var btnLogin$ = Rx.Observable.fromEvent(btn, 'click');
        btnLogin$.subscribe(function (x) {
            btn.style.display = 'none';
            var formLogin = document.getElementById('saveLoginForm');
            formLogin.style.display = 'block';
        });
    }
    var navBar2nd = $('.navbar-2nd');
    if (navBar2nd.hasClass('standalone')) return;
    var navLogo = $('.navbar-2nd .navbar-brand');
    var navLinks = $('.navbar-2nd .nav-onscroll');
    var navTop = $('.navbar-2nd .nav-ontop');
    var scroll$ = Rx.Observable.fromEvent(window, 'scroll')
        .map(function(e){
            return window.pageYOffset;
        })
        .startWith(window.pageYOffset);

    scroll$.subscribe(function(x){
        if (x < 71) {
            navBar2nd.css({
                'position': 'absolute',
                'top': '71px'
            });
            navLogo.hide();
            navTop.show();
            navLinks.hide();
        } else {
            navBar2nd.css({
                'position': 'fixed',
                'top': '0px'
            });
            navLogo.show();
            navTop.hide();
            navLinks.css({
                'display': 'inline-block'
            });
        }
    });

});