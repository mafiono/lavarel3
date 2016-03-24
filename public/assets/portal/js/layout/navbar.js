/**
 * Created by miguel on 08/03/2016.
 */
$(function ($) {
    'use strict';
    var btn = document.getElementById('btnLogin');
    var formLogin = document.getElementById('saveLoginForm');
    var divLogin = $('#form-login');
    var btnSearch = $('#btn-search');
    var textSearch = $('.nav-ontop input');
    var showSearch = false;
    if (btn != null) {
        var btnLogin$ = Rx.Observable.fromEvent(btn, 'click');
        btnLogin$.subscribe(function (x) {
            btn.style.display = 'none';
            formLogin.style.display = 'block';
            divLogin.removeClass('col-xs-2').toggleClass('col-xs-4', true);
            btnSearch.parent().removeClass('col-xs-4').toggleClass('col-xs-2', true);
            showSearch = false;
            textSearch.hide();
        });
    }
    btnSearch.click(function(){
        showSearch = !showSearch;
        if (showSearch) {
            divLogin.removeClass('col-xs-4').toggleClass('col-xs-2', true);
            btnSearch.parent().removeClass('col-xs-2').toggleClass('col-xs-4', true);
            textSearch.show();
            textSearch.focus();
        } else {
            divLogin.removeClass('col-xs-2').toggleClass('col-xs-4', true);
            btnSearch.parent().removeClass('col-xs-4').toggleClass('col-xs-2', true);
            textSearch.hide();
        }
        btn.style.display = 'block';
        formLogin.style.display = 'none';
    });


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