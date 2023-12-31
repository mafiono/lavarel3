<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<html lang="pt" class="no-js">
<head>

    @include('portal.meta_tags')

    <link rel="stylesheet" href="/assets/portal/css/style.css?v={{ config('app.rand_hash') }}" />
    <link rel="stylesheet" href="/assets/portal/css/app.css?v={{ config('app.rand_hash') }}" />
    <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">

    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-101734225-1', 'auto');
        ga('send', 'pageview');

    </script>

    @yield('styles')
    @yield('header')

    <meta property="og:site_name" content="CASINO PORTUGAL"/>
    <meta property="og:type" content="website"/>
    <meta property="og:title" content="CASINO PORTUGAL - Apostas Desportivas e Casino online"/>
    <meta property="og:description" content="Faça o registo e comece já a ganhar! Oferecemos 10€ para jogar em slots Casino Portugal." />
    <meta property="og:image" content="https://www.casinoportugal.pt/assets/portal/img/logo.png"/>
    <meta property="og:url" content="https://www.casinoportugal.pt{{ $casino ? '/casino' : ''  }}" />
    <meta name="apple-itunes-app" content="app-id=1353477331">


    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <script>window.casinoAvailable = '{{config('app.casino_available')}}';</script>
</head>
<style>
    #chat{
        position:fixed;
        z-index:1;
        bottom:15px;
        right:15px;
    }
    h1 {
        padding: 0;
        margin: inherit !important;
        font-size: inherit !important;
        font-weight: inherit !important;
        line-height: inherit !important;
    }
</style>
<body>
@include('portal.mobile-loader')
<div class="bet">
    @include('layouts.header.header')
    <mobile-app-banner></mobile-app-banner>
    <mobile-header
        user-login-time="{{Session::has('user_login_time') ? 'data-time=' . Session::get('user_login_time') .'000': ''}}"
        server-time="{{Carbon\Carbon::now()->getTimestamp()}}000"
        context="{{$casino ? 'casino' : 'sports'}}"
    >
    </mobile-header>
    @if(!$casino)
        <mobile-login></mobile-login>
        <mobile-menu></mobile-menu>
    @endif

    @if($authUser)
    <div id="chat">
       <a href="/perfil/comunicacao/mensagens"> <img src="/assets/portal/img/chat.png"></a>
    </div>
    @endif

    @yield('content')

    @include('layouts.footer')

    <mobile-up-button></mobile-up-button>
    @if (!$casino)
        <mobile-bet-alert></mobile-bet-alert>
        <mobile-betslip-button></mobile-betslip-button>
        <footer-hider></footer-hider>
    @endif
    <cookies-consent></cookies-consent>
</div>
<script src="/assets/portal/js/bundle.js?v={{ config('app.rand_hash') }}"></script>

@yield('scripts')
@include('portal.popup-alert')

@if ($authUser)
    <script>
        $(function() {
            setInterval(function() {
                $.getJSON("{!! route('balance') !!}")
                    .done(function (data) {
                        if (data.length === 0) {
                            top.location.reload();
                        }
                        $("#headerBalance").html(data.total);
                        $("#popupBalance").html(data.balance);
                        $("#popupBonus").html(data.bonus);
                        $("#popupBalanceTotal").html(data.total);
                        $(".messages-count").html(data.unreads > 0 ? data.unreads : '');

                        if (Store) {
                            Store.user.balance = data.balance;
                            Store.user.bonus = data.bonus;
                            Store.user.totalBalance = data.total;
                            Store.user.unreads = data.unreads;
                        }
                    });
            }, {{env('BALANCE_LOOP', 3000)}});
        });
    </script>
@endif
@if (Session::has('lastSession'))
    @if(Session::remove('show_consent')!==null)
        <script>
            $.fn.popup({
                    type: 'info',
                    html: true,
                    text: 'Última sessão em {{Session::get('lastSession')}}.<br> Desejo ser contactado pelos meios de comunicação já definidos. Consulte a nova versão da' +
                    '<a href="https://www.casinoportugal.pt/info/politica_privacidade"> Política de Privacidade </a>' + ' e ' +
                    '<a href="https://www.casinoportugal.pt/info"> Termos e Condições </a>' + '.',
                    showConfirmButton: true,
                    allowOutsideClick: true,
                    confirmButtonText: 'SIM',
                },
            );
        </script>
    @else
        <script>
            $(function () {
                $.fn.popup({
                    title: 'Última sessão',
                    text: 'em {{Session::get('lastSession')}}',
                    timer: 5000
                });
            });
        </script>
    @endif
@endif
<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>

<!--Start of Tawk.to Script-->
@if (!$authUser)
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

    Tawk_API.onLoad = function () {
        MobileHelper.handleTalkTo()
    };

    Tawk_API.onChatMinimized = function () {
        MobileHelper.handleTalkTo();
    };

    window.setInterval(function() {
        MobileHelper.handleTalkTo();
    }, 1000);
</script>
@endif
<!--End of Tawk.to Script-->

<!--[if lt IE 7]>
<p class="old-ie">Você está a usar um browser <strong>desatualizado</strong>. Por favor <a href="http://windows.microsoft.com/pt-pt/internet-explorer/download-ie">Atualize o seu Browser</a> para melhorar a sua experiência no nosso site.</p>
<![endif]-->
</body>
</html>
