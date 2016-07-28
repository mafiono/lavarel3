<div class="brand-back brand-box-title bs-wp">
    <div class="col-xs-2 main-title fleft">
        Registo
    </div>
    <div class="col-xs-10 brand-title aright white-color fleft">
        {!! $text or '' !!}
        @if (!isset($close) || $close)
            {{--<a href="/" class="btn-close">X</a>--}}
        @endif
    </div>
    <div class="clear"></div>
</div>