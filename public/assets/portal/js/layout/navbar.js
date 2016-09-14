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
        var resetPass = $('#reset_pass');
        var isReset = false;
        btnReset.click(function(e){
            e.preventDefault();
            isReset = !isReset;
            if (isReset) {
                resetPass.removeClass('hidden').show().addClass('fadeInDown animated')
                    .one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function(){
                        $(this).removeClass('fadeInDown animated');
                    });
            } else  {
                resetPass.removeClass('hidden').show().addClass('fadeOutUp animated')
                    .one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function(){
                        $(this).removeClass('fadeOutUp animated').addClass('hidden');
                    });
            }
        });
    }

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