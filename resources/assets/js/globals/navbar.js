import {Observable} from 'rxjs/Observable';
import 'rxjs/add/observable/fromEvent';
import 'rxjs/add/observable/interval';
import 'rxjs/add/operator/map';
import 'rxjs/add/operator/startWith';

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
        var btnLogin$ = Observable.fromEvent(btn, 'click');
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
    btnSearch.click(function(e){
        e.preventDefault();
        e.stopPropagation();
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
        if (btn)
            btn.style.display = 'block';
        if (formLogin)
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
                showCancelButton: true,
                cancelButtonText: 'Sair',
                closeOnConfirm: false
            }, function (email) {
                var valid = /^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/.test( email );
                if (!valid) {
                    swal.showInputError("Introduza um email válido!");
                    return false;
                }
                $.post('/recuperar_password', {
                    'reset_email': email
                })
                    .success(function (data) {
                        $.fn.popup({
                            title: 'Recuperar Palavra passe',
                            text: data.msg
                        });
                    })
                    .error(function (err) {
                        var obj = err.responseJSON;
                        swal.showInputError(obj.msg);
                    });
                swal.disableButtons();
            });
        });
    }

    var startBrowser = new Date().getTime();
    var startServerTimer = $('.server-time');
    var startUserTimer = $('.user-time');

    if (startServerTimer.data('time')) {
        var startServer = Number(startServerTimer.data('time'));
        Observable.interval(1000)
            .map(function () { return startBrowser - startServer; })
            .map(function (diff) { return new Date().getTime() + diff; })
            .map(function (t) { return new Date(t).toTimeString().substr(0, 8); })
            .subscribe(function (time) {startServerTimer.text(time + ' (GMT)'); });

        if (startUserTimer.data('time')) {
            var startUser =  Number(startUserTimer.data('time'));
            var calcUserTime = startServer - startUser;
            var userSpan = startUserTimer.find('span');
            startUserTimer.removeClass('hide');
            Observable.interval(1000)
                .startWith(0)
                .map(function () { return new Date().getTime() - startBrowser + calcUserTime; })
                .map(function (t) { return new Date(t).toISOString().substr(11, 8); })
                .subscribe(function (time) { userSpan.text(time); });
        } else startUserTimer.hide();
    } else startUserTimer.hide();

    var navOnScroll = $('.navbar-2nd .nav-onscroll');
    var scroll$ = Observable.fromEvent(window, 'scroll')
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
            navOnScroll.hide();
        } else {
            navBar2nd.css({
                'position': 'fixed',
                'top': '0px'
            });
            navOnScroll.show();
        }
    });
});