<div class="settings-col-20">
    <h2 class="settings-title">
        Histórico
    </h2>
    <div id="menu-container">
        <a class="settings-button settings-menu-margin  {{($active == 'APOSTAS RECENTES')?"settings-button-selected":"" }}" href="/historico/recente">
            APOSTAS RECENTES
        </a>
        <!--
        <a class="settings-button {{($active == 'DEPOSITOS')?"settings-button-selected":"" }}" href="/historico/depositos">
            DEPÓSITOS
        </a>
        <a class="settings-button {{($active == 'LEVANTAMENTOS')?"settings-button-selected":"" }}" href="/historico/levantamentos">
            LEVANTAMENTOS
        </a>
        -->

        <a class="settings-button settings-menu-margin {{($active == 'HISTORICO OPERACOES')?"settings-button-selected":"" }}" href="/historico/operacoes">
            HISTÓRICO OPERAÇÕES
        </a>
    </div>
    @if ($active == 'HISTORICO OPERACOES')
    <div id="history-filters-container" class="{{($active == 'HISTORICO OPERACOES')?"":"hidden"}}" style="overflow: auto">
        {!! Form::open(['url' => '/historico/operacoes', 'id' => 'operations-filter-form']) !!}
        <div class="settings-date-interval settings-menu-margin">
            <input type="text" id="date-begin-text" name="date_begin" class="fleft" value="{{$input['date_begin'] or \Carbon\Carbon::now()->subMonth(1)->format('d/m/y')}}" readonly>
                -
            <input type="text" id="date-end-text" name="date_end" class="fright" value="{{$input['date_end'] or \Carbon\Carbon::now()->format('d/m/y')}}" readonly>
        </div>
        <div id="date-picker"></div>
        <div class="settings-row">
            <div class="settings-row-label">Apostas</div>
            <div class="fright">
                <input id="bets-filter" name="operations_filter" type="checkbox" class="settings-switch" value="bets" checked>
                <label for="bets-filter"></label>
            </div>
        </div>
        <div class="settings-row">
            <div class="settings-row-label">Movimentos</div>
            <div class="fright">
                <input id="transactions-filter" name="operations_filter" type="checkbox" class="settings-switch" value="transactions">
                <label for="transactions-filter"></label>
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

<style>

#history-filters-container {
    overflow:scroll;
    max-height: 340px;
}

#history-filters-container .settings-row-label {
    display: inline-block;
    line-height: 28px;
    color: #444;
    font-size: 120%;
}

#history-filters-container .settings-row {
    border-bottom: 1px solid #ccc;
}

/* date picker */
.ui-datepicker {
    font-size: 81%;
    border-collapse: collapse;
    margin: 15px auto 0;
    max-width: 178px;
}
.ui-datepicker td {
    padding: 0;
}
.ui-datepicker .ui-state-default {
    border: 0;
    text-align: center;
}
.ui-datepicker .ui-state-highlight {
    background: #f6f6f6;
    color: #000;
    font-weight: bold;
}
.ui-datepicker .ui-state-active {
    background: #019fe9;
    color: #FFF;
    border-radius: 2px;
}

.ui-datepicker-title > span {
    display: inline !important;
}

.date-range-selected > .ui-state-active,
.date-range-selected > .ui-state-default {
    background: none;
    background-color: lightsteelblue;
}
</style>

{!! HTML::script(URL::asset('/assets/portal/js/history/operations.js')); !!}
