<li class="header-live"><a href="/direto" title="DIRETO">DIRETO</a></li>
<li class="header-prematch"><a href="/desportos" title="DESPORTOS">DESPORTOS</a></li>
<li class="header-casino {{$casino ? 'active' : ''}}">
    <a href="{{config('app.casino_available') && !$casino ? '/casino' : 'javascript:void(0)'}}" title="CASINO">CASINO</a>
</li>