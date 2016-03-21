<div class="settings-col-20">
    <h2 class="settings-title">
        Histórico
    </h2>
    {!! Form::open(['url' => '/historico/operacoes', 'id' => 'operations-filter-form']) !!}
    <div id="menu-container">
        {{--<a class="settings-button settings-menu-margin  {{($active == 'APOSTAS RECENTES')?"settings-button-selected":"" }}" href="/historico/recente">--}}
            {{--APOSTAS RECENTES--}}
        {{--</a>--}}
        {{--<!----}}
        {{--<a class="settings-button {{($active == 'DEPOSITOS')?"settings-button-selected":"" }}" href="/historico/depositos">--}}
            {{--DEPÓSITOS--}}
        {{--</a>--}}
        {{--<a class="settings-button {{($active == 'LEVANTAMENTOS')?"settings-button-selected":"" }}" href="/historico/levantamentos">--}}
            {{--LEVANTAMENTOS--}}
        {{--</a>--}}
        {{---->--}}
        {{--<a class="settings-button settings-menu-margin {{($active == 'HISTORICO OPERACOES')?"settings-button-selected":"" }}" href="/historico/operacoes">--}}
            {{--HISTÓRICO OPERAÇÕES--}}
        {{--</a>--}}
        <div class="settings-date-interval settings-menu-margin">
            <input type="text" id="date-begin-text" name="date_begin" class="fleft" value="{{$input['date_begin'] or \Carbon\Carbon::now()->subMonth(1)->format('d/m/y')}}" readonly>
            -
            <input type="text" id="date-end-text" name="date_end" class="fright" value="{{$input['date_end'] or \Carbon\Carbon::now()->format('d/m/y')}}" readonly>
        </div>
        <div id="date-picker"></div>
    </div>
    @if ($active == 'HISTORICO OPERACOES')
    <div id="history-filters-container" class="{{($active == 'HISTORICO OPERACOES')?"":"hidden"}}">
        <div class="settings-row">
            <div class="settings-row-label">Depósitos</div>
            <div class="fright">
                <input id="deposits-filter" name="deposits_filter" type="checkbox" class="settings-switch" value="deposits">
                <label for="deposits-filter"></label>
            </div>
        </div>
        <div class="settings-row">
            <div class="settings-row-label">Levantamentos</div>
            <div class="fright">
                <input id="withdraws-filter" name="withdraws_filter" type="checkbox" class="settings-switch" value="withdraws">
                <label for="withdraws-filter"></label>
            </div>
        </div>
        <div class="settings-row">
            <div class="settings-row-label">Apostas Casino</div>
            <div class="fright">
                <input id="casino-bets-filter" name="casino_bets_filter" type="checkbox" class="settings-switch" value="casino-bets">
                <label for="casino-bets-filter"></label>
            </div>
        </div>
        <div class="settings-row">
            <div class="settings-row-label">Apostas Desporto</div>
            <div class="fright">
                <input id="sports-bets-filter" name="sports_bets_filter" type="checkbox" class="settings-switch" value="sports-bets">
                <label for="sports-bets-filter"></label>
            </div>
        </div>
    </div>
    {!! Form::close() !!}
    @endif
</div>

{!! HTML::style('assets/portal/css/jquery-ui.css'); !!}

{!! HTML::script('assets/portal/js/moment.min.js'); !!}
{!! HTML::script('assets/portal/js/jquery-ui.js'); !!}
{!! HTML::script('assets/portal/js/datepicker-pt.js'); !!}
{!! HTML::script('assets/portal/js/plugins/jquery-slimscroll/jquery.slimscroll.min.js'); !!}

<style>
    .bet .settings-col-20 {
        min-width: 188px;
        padding-right: 5px;
        overflow: hidden;
    }
    .bet #menu-container {
        padding-right: 10px;
    }
    .bet .settings-menu-mid-margin{
        margin: 9px auto 0;
    }
    .bet #history-filters-container {
        overflow-x: hidden;
        overflow-y: auto;
        max-height: 320px;
    }
    .bet #history-filters-container .settings-row {
        max-width: 178px;
    }

    .bet #history-filters-container .settings-row-label {
        display: inline-block;
        line-height: 28px;
        color: #444;
        font-size: 120%;
    }

    .bet #history-filters-container .settings-row {
        border-bottom: 1px solid #ccc;
    }

    /* date picker */
    .bet #date-picker {
        min-height: 140px;
    }

    .bet .ui-datepicker {
        font-size: 81%;
        border-collapse: collapse;
        margin: 15px auto 0;
        max-width: 178px;
        width: 100%;
    }
    .bet .ui-datepicker td {
        padding: 0;
    }
    .bet .ui-datepicker .ui-state-default {
        border: 0;
        text-align: center;
    }
    .bet #date-picker  .ui-state-highlight {
        background-color: #ff8f2b;
        color: #000;
        font-weight: bold;
    }
    .bet #date-picker  .ui-state-hover {
        background: #ffb734;
    }
    .bet .ui-datepicker .ui-state-active {
        background: #019fe9;
        color: #FFF;
    }

    .bet .ui-datepicker-title > span {
        display: inline !important;
    }

    .bet #date-picker .ui-datepicker {
        border: 0;
        border-radius: 0;
    }
    .bet #date-picker .ui-datepicker-header {
        border: 0;
        border-radius: 0;
        background-color: #019fe9;
        color: #FFFFFF;
    }
    .bet #date-picker .ui-datepicker-calendar thead {
        background-color: #019fe9;
        color: #FFFFFF;
        border-top: 1px solid #FFFFFF;
        border-bottom: 5px solid #FFFFFF;
    }
    .bet #date-picker td a {
        padding: 0.4em;
    }

    .bet .date-range-selected > .ui-state-active,
    .bet .date-range-selected > .ui-state-default {
        background: none;
        background-color: #ffd756;
    }
    .bet .ui-datepicker th {
        padding: .7em 0;
    }
    .bet .form-registo form span.ui-icon {
        margin-left: -8px;
    }
</style>

{!! HTML::script(URL::asset('/assets/portal/js/history/operations.js')); !!}
