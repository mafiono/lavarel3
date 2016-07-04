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

    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="assets/portal/img/favicon-144.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="assets/portal/img/favicon-114.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="assets/portal/img/favicon-72.png">
    <link rel="apple-touch-icon-precomposed" href="assets/portal/img/favicon-57.png">
    <link rel="shortcut icon" type="image/x-icon" href="/assets/portal/img/favicon.ico"/>

    {!! HTML::style('assets/portal/css/normalize.css'); !!}
    {!! HTML::style('assets/portal/css/animate.css'); !!}
    {!! HTML::style('assets/portal/awesome/css/font-awesome.min.css'); !!}
    {!! HTML::style('assets/portal/bootstrap/ibetup.css'); !!}

    {!! HTML::style('assets/portal/newstyle/style.css'); !!}


    <script src="/assets/portal/js/jquery.min.js"></script>
    <script src="/assets/portal/js/viewportchecker.js"></script>
    <script src="/assets/portal/js/plugins/rx.umd.min.js"></script>
    <script src="/assets/portal/js/layout/navbar.js"></script>

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

<body class="bet">

    @include('layouts.header.header')

    @yield('content')

    @include('layouts.footer')

    <script src="/assets/portal/js/animate.js"></script>

    {!! HTML::script(URL::asset('/assets/portal/js/plugins/jquery-form/jquery.form.min.js')); !!}
    {!! HTML::script(URL::asset('/assets/portal/js/forms.js')); !!}

    @yield('scripts')

    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>

    <!---<script>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

      ga('create', 'UA-00000000-0', 'auto');
      ga('require', 'displayfeatures');
      ga('require', 'linkid', 'linkid.js');
      ga('send', 'pageview');

    </script>--->
<!-- Start of 6paqhelp Zendesk Widget script -->
<script defer>/*<![CDATA[*/window.zEmbed||function(e,t){var n,o,d,i,s,a=[],r=document.createElement("iframe");window.zEmbed=function(){a.push(arguments)},window.zE=window.zE||window.zEmbed,r.src="javascript:false",r.title="",r.role="presentation",(r.frameElement||r).style.cssText="display: none",d=document.getElementsByTagName("script"),d=d[d.length-1],d.parentNode.insertBefore(r,d),i=r.contentWindow,s=i.document;try{o=s}catch(c){n=document.domain,r.src='javascript:var d=document.open();d.domain="'+n+'";void(0);',o=s}o.open()._l=function(){var o=this.createElement("script");n&&(this.domain=n),o.id="js-iframe-async",o.src=e,this.t=+new Date,this.zendeskHost=t,this.zEQueue=a,this.body.appendChild(o)},o.write('<body onload="document._l();">'),o.close()}("https://assets.zendesk.com/embeddable_framework/main.js","6paqhelp.zendesk.com");
    /*]]>*/</script>
<!-- End of 6paqhelp Zendesk Widget script -->
<!--Start of Zopim Live Chat Script-->
<script type="text/javascript">
    //mail: zmcrvypx@6paq.com
    //zend: 6paqhelp.zendesk.com

    zE(function() {
        zE.setLocale('pt');
        zE.hide();

        $zopim(function(){
            $zopim.livechat.button.show();
            $zopim.livechat.setOnConnected(function(){
                console.log('Connected');
                $zopim.livechat.button.show();
            });
            $zopim.livechat.window.onHide(function(){
                setTimeout(function(){
                    $zopim.livechat.button.show();
                }, 0);
                console.log('setOnChatEnd');
            });

        });
        @if (isset($authUser))
            zE.identify({
                name: '{!! $authUser->profile->name !!}',
                email: '{{$authUser->profile->email}}',
                externalId: '{{$authUser->id}}'
            });
        @endif
    });

    {{--window.$zopim||(function(d,s){var z=$zopim=function(c){z._.push(c)},$=z.s=--}}
            {{--d.createElement(s),e=d.getElementsByTagName(s)[0];z.set=function(o){z.set.--}}
    {{--_.push(o)};z._=[];z.set._=[];$.async=!0;$.setAttribute("charset","utf-8");--}}
        {{--$.src="//v2.zopim.com/?3nXcrtnWdBgxUb0BebJlOM9we4bYFJRv";z.t=+new Date;$.--}}
                {{--type="text/javascript";e.parentNode.insertBefore($,e)})(document,"script");--}}
    {{--$zopim(function() {--}}
{{--//        $zopim.livechat.setLanguage('pt');--}}
{{--//        $zopim.livechat.theme.setColor('#112149');--}}
{{--//        $zopim.livechat.button.show();--}}
        {{--@if (isset($authUser))--}}
        {{--$zopim.livechat.set({--}}
            {{--name: '{!! $authUser->profile->name !!}',--}}
            {{--email: '{{$authUser->profile->email}}',--}}
            {{--externalId: '{{$authUser->id}}'--}}
        {{--});--}}
        {{--@endif--}}
{{--//        $zopim.livechat.theme.setTheme('classic');--}}
{{--//        $zopim.livechat.theme.reload(); // apply new theme settings--}}
        {{--console.log('Zopim loaded');--}}
    {{--});--}}
    function showChat(){
        //zE.activate();
        $zopim(function() {
            $zopim.livechat.window.show();
        });
        return false;
    }
</script>
<!--End of Zopim Live Chat Script-->



</body>
</html>
