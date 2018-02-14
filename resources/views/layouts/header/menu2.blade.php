<li class="header-prematch">
    <a href="/"  style="font-size: 13px!important;"  title="DESPORTOS">
        @if($sports)
            <h1>DESPORTOS</h1>
        @else
            <span>DESPORTOS</span>
        @endif
    </a>
</li>
<li class="header-casino {{$casino ? 'active' : ''}}">
    <a href="/casino" style="font-size: 13px!important;" title="CASINO">
        @if($casino)
            <h1>CASINO</h1>
        @else
            <span>CASINO</span>
        @endif
    </a>
</li>
