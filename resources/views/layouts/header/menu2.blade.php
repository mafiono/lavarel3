<li class="header-prematch">
    <a href="/" title="DESPORTOS">
        @if($sports)
            <h3>DESPORTOS</h3>
        @else
            <h4>DESPORTOS</h4>
        @endif
    </a>
</li>
<li class="header-casino {{$casino ? 'active' : ''}}">
    <a href="/casino" title="CASINO">
        @if($casino)
            <h3>CASINO</h3>
        @else
            <h4>CASINO</h4>
        @endif
    </a>
</li>
<li class="header-live"><promotions-link></promotions-link></li>
<li class="header-golodeouro">
    <a href="/golodeouro" title="GOLO D'OURO">
        @if($golodeouro)
            <h3>GOLO D'OURO</h3>
        @else
            <h4>GOLO D'OURO</h4>
        @endif
    </a>
</li>