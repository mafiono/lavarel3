<li class="header-prematch">
    <a href="/" title="DESPORTOS">
        @if($sports)
            <h1>DESPORTOS</h1>
        @else
            <span>DESPORTOS</span>
        @endif
    </a>
</li>
<li class="header-casino {{$casino ? 'active' : ''}}">
    <a href="/casino" title="CASINO">
        @if($casino)
            <h1>CASINO</h1>
        @else
            <span>CASINO</span>
        @endif
    </a>
</li>
<li class="header-live"><promotions-link></promotions-link></li>
<li class="header-golodeouro">
    <a href="/golodeouro" title="GOLO D'OURO">
        @if($golodeouro)
            <h1>GOLO D'OURO</h1>
        @else
            <span>GOLO D'OURO</span>
        @endif
    </a>
</li>