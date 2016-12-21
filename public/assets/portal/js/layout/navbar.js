/**
 * Created by miguel on 08/03/2016.
 * Changed by diogo on 10/08/2016.
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
    $(document).mouseup(function (e)
    {
        if (showSearch) {
            showSearch = !showSearch;
        }

        var container = btnSearch;

        if (!container.is(e.target) // if the target of the click isn't the container...
            && container.has(e.target).length === 0) // ... nor a descendant of the container
        {
            divLogin.removeClass('col-xs-2').toggleClass('col-xs-4', true);
            btnSearch.parent().removeClass('col-xs-4').toggleClass('col-xs-2', true);
            textSearch.hide();
        }
    });
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
    var btnReset = $('#btn_reset_pass');
    if (btnReset.length){
        btnReset.click(function(e){
            e.preventDefault();
            e.stopPropagation();
            $.fn.popup({
                title: 'Recuperar Palavra Passe',
                text: 'Por favor, indique o seu email',
                type: 'input',
                customClass: 'caspt email',
                closeOnConfirm: false
            }, function (email) {
                // TODO Enviar este dados por AJAX.
                console.log(email);
                $.fn.popup({
                    title: 'Recuperar Palavra passe',
                    text: 'Foi enviado um email para confirmação',
                });
            });
        });
    }

    var startBrowser = new Date().getTime();
    var serverTimer = $('.server-time');
    if (serverTimer.data('time')) {
        var startServer = Number(serverTimer.data('time'));
        Rx.Observable.interval(1000)
            .map(function () { return startBrowser - startServer; })
            .map(function (diff) { return new Date().getTime() + diff; })
            .map(function (t) { return new Date(t).toLocaleTimeString(); })
            .subscribe(function (time) {serverTimer.text(time); });
    }
    var userTimer = $('.user-time');
    if (userTimer.data('time')) {
        var startUser =  Number(userTimer.data('time'));
        Rx.Observable.interval(1000)
            .map(function () { return startBrowser - startUser; })
            .map(function (diff) { return new Date().getTime() - startBrowser + diff; })
            .map(function (t) { return new Date(t).toLocaleTimeString(); })
            .subscribe(function (time) {userTimer.text(time); });
    }


    if (navBar2nd.hasClass('standalone')) return;
    var navLogo = $('.navbar-2nd .navbar-brand');
    var navOnScroll = $('.navbar-2nd .nav-onscroll');
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
            navOnScroll.hide();
        } else {
            navBar2nd.css({
                'position': 'fixed',
                'top': '0px'
            });
            navLogo.show();
            navOnScroll.show();
        }
    });
});