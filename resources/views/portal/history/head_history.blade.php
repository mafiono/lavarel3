<div class="box-links">

    {!! Form::open(['url' => '/historico/operacoes', 'id' => 'operations-filter-form']) !!}
    <div id="menu-container">
        <div class="settings-date-interval settings-menu-margin">
            <input type="text" id="date-begin-text" name="date_begin" class="fleft" value="{{$input['date_begin'] or \Carbon\Carbon::now()->subMonth(1)->format('d/m/y')}}" readonly>
            -
            <input type="text" id="date-end-text" name="date_end" class="fright" value="{{$input['date_end'] or \Carbon\Carbon::now()->format('d/m/y')}}" readonly>
        </div>
        <div id="date-picker"></div>
    </div>
    <div id="history-filters-container" class="{{($active == 'operacoes')?"":"hidden"}}">
        <div class="settings-row">
            <div class="settings-row-label">Apostas Desporto</div>
            <div class="fright">
                <input id="sports-bets-filter" name="sports_bets_filter" type="checkbox" checked="checked" class="settings-switch" value="sports-bets">
                <label for="sports-bets-filter"></label>
            </div>
        </div>
        <div class="settings-row">
            <div class="settings-row-label">Apostas Casino</div>
            <div class="fright">
                <input id="casino-bets-filter" name="casino_bets_filter" type="checkbox" checked="checked" class="settings-switch" value="casino-bets">
                <label for="casino-bets-filter"></label>
            </div>
        </div>
        <div class="settings-row">
            <div class="settings-row-label">Depósitos</div>
            <div class="fright">
                <input id="deposits-filter" name="deposits_filter" type="checkbox" checked="checked" class="settings-switch" value="deposits">
                <label for="deposits-filter"></label>
            </div>
        </div>
        <div class="settings-row">
            <div class="settings-row-label">Levantamentos</div>
            <div class="fright">
                <input id="withdraws-filter" name="withdraws_filter" type="checkbox" checked="checked" class="settings-switch" value="withdraws">
                <label for="withdraws-filter"></label>
            </div>
        </div>
    </div>
    {!! Form::close() !!}
</div>
{!! HTML::style('assets/portal/css/jquery-ui.css') !!}
{!! HTML::style('assets/portal/css/bet-details.css') !!}

{!! HTML::script('assets/portal/js/moment.min.js') !!}
{!! HTML::script('assets/portal/js/jquery-ui.js') !!}
{!! HTML::script('assets/portal/js/datepicker-pt.js') !!}
{!! HTML::script('assets/portal/js/plugins/jquery-slimscroll/jquery.slimscroll.min.js') !!}

{!! HTML::script(URL::asset('/assets/portal/js/handlebars/handlebars.min.js')) !!}
{!! HTML::script(URL::asset('/assets/portal/js/handlebars/handlebars.custom.js')) !!}

{!! HTML::script(URL::asset('/assets/portal/js/template.js')) !!}

{!! HTML::script(URL::asset('/assets/portal/js/registo/tooltip.js')) !!}
{!! HTML::script(URL::asset('/assets/portal/js/history/operations.js')) !!}
