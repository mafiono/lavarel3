<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<html lang="pt" class="no-js">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta http-equiv="Revisit-After" content="1 Day"/>
    <meta http-equiv="Content-Language" content="pt" />

    <meta property="og:site_name" content="BetPortugal.pt"/>
    <meta property="og:type" content="website"/>

    <meta name="keywords" content="sport betting,live sports betting,online betting,bet and win,online football,bet online,soccer bets,champions league,barclays premier league,football betting site" />
    <meta name="Rating" content="General"/>
    <meta name="distribution" content="Global" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <meta name="Robots" content="index,follow"/>
    <meta name="Author" content="Webhouse.pt"/>
    <meta name="Email" content="{{env('MAIL_USERNAME')}}"/>
    <meta name="Copyright" content="Agosto 2015"/>

    <link rel="icon" href="/assets/portal/img/favicon.ico"/>

    {!! HTML::style('assets/portal/css/normalize.css?v='.config('app.rand_hash')) !!}
    {!! HTML::style('assets/portal/css/animate.css?v='.config('app.rand_hash')) !!}
    {!! HTML::style('assets/portal/awesome/css/font-awesome.min.css?v='.config('app.rand_hash')) !!}
    {!! HTML::style('assets/portal/bootstrap/ibetup.css?v='.config('app.rand_hash')) !!}
    <link media="all" type="text/css" rel="stylesheet" href="/assets/portal/css/portal.css?v={{ config('app.rand_hash') }}">
    {!! HTML::style('assets/portal/css/style.css?v='.config('app.rand_hash')) !!}


    <script src="/assets/portal/js/jquery.min.js?v={{ config('app.rand_hash') }}"></script>
    <script src="/assets/portal/js/viewportchecker.js?v={{ config('app.rand_hash') }}"></script>
    <script src="/assets/portal/js/plugins/rx.umd.min.js?v={{ config('app.rand_hash') }}"></script>
    <script src="/assets/portal/js/layout/navbar.js?v={{ config('app.rand_hash') }}"></script>

    @yield('styles')

    <!--[if lt IE 7]>
    <p>Você está a usar um browser <strong>desatualizado</strong>. Por favor <a href="http://windows.microsoft.com/pt-pt/internet-explorer/download-ie">Atualize o seu Browser</a> para melhorar a sua experiência no nosso site.</p>
    <![endif]-->

    <meta property="og:image" content="assets/portal/img/logo.jpg"/>
    <meta property="og:title" content="Apostas desportivas online - Poker e jogos de Casino online - BetPortugal.pt"/>

    <title>Apostas desportivas online - Poker e jogos de Casino online - BetPortugal.pt</title>
    <meta name="description" content="BetPortugal Apostas online nos principais eventos desportivos - Futebol, Ténis, Futsal - Registe-se já e garanta o seu bónus na sua primeira aposta." />

    <meta name="csrf-token" content="{{ csrf_token() }}" />

    @yield('header')

</head>

<body>
<div class="bet">
    @include('layouts.header.header', ['casino' => true])

    @yield('content')

    @include('layouts.footer')
</div>

<script src="/assets/portal/js/animate.js?v={{ config('app.rand_hash') }}"></script>

{!! HTML::script(URL::asset('/assets/portal/js/plugins/jquery-form/jquery.form.min.js?v='.config('app.rand_hash'))) !!}
{!! HTML::script(URL::asset('/assets/portal/js/forms.js?v='.config('app.rand_hash'))) !!}

@yield('scripts')


<script>
    function onPopup(url) {
        // Fixes dual-screen position                         Most browsers      Firefox
        var dualScreenLeft = window.screenLeft != undefined ? window.screenLeft : screen.left;
        var dualScreenTop = window.screenTop != undefined ? window.screenTop : screen.top;

        var width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
        var height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;

        var w = 800, h = 600, title = "Bet Portugal FAQ";

        var left = ((width / 2) - (w / 2)) + dualScreenLeft;
        var top = ((height / 2) - (h / 2)) + dualScreenTop;
        var newWindow = window.open(url, title, 'scrollbars=yes, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);

        // Puts focus on the newWindow
        if (window.focus) {
            newWindow.focus();
        }
    }
</script>

<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>

<!--Start of Tawk.to Script-->
<script type="text/javascript">
    var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
    (function(){
        var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
        s1.async=true;
        s1.src='https://embed.tawk.to/576a9987b57c05002099a2e3/default';
        s1.charset='UTF-8';
        s1.setAttribute('crossorigin','*');
        s0.parentNode.insertBefore(s1,s0);
    })();
</script>
<!--End of Tawk.to Script-->
<script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

    ga('create', 'UA-96987410-1', 'auto');
    ga('send', 'pageview');

</script>

</body>
</html>
