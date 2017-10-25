<li class="header-live">
    <a href="/direto" title="DIRETO">
    @if($live)
        <h1>DIRETO</h1>
    @else
        <span>DIRETO</span>
    @endif
    </a>
</li>
<li class="header-prematch">
    <a href="/desportos" title="DESPORTOS">
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