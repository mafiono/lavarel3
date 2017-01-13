<div class="box-links">
    {!! Form::open(['url' => '/historico/operacoes', 'id' => 'operations-filter-form']) !!}
    <div class="row history dates">
        <div class="col-xs-12">
            @include('portal.partials.input-date', [
                'name' => 'De',
                'hiddenLabel' => true,
                'field' => 'date_begin',
                'value' => $input['date_begin'] ?? \Carbon\Carbon::now()->subMonth(1)->format('d/m/y'),
                'cols' => 12
            ])
            @include('portal.partials.input-date', [
                'name' => 'Até',
                'hiddenLabel' => true,
                'field' => 'date_end',
                'value' => $input['date_end'] ?? \Carbon\Carbon::now()->format('d/m/y'),
                'cols' => 12
            ])
        </div>
    </div>
    <div class="row history">
        @include('portal.communications.input-radio', [
            'fieldName' => 'Desporto',
            'field' => 'sports_bets_filter',
            'value' => 1,
            'cols' => 12
        ])
        @include('portal.communications.input-radio', [
            'fieldName' => 'Casino',
            'field' => 'casino_bets_filter',
            'value' => 1,
            'cols' => 12
        ])
        @include('portal.communications.input-radio', [
            'fieldName' => 'Depósitos',
            'field' => 'deposits_filter',
            'value' => 1,
            'cols' => 12
        ])
        @include('portal.communications.input-radio', [
            'fieldName' => 'Levantamentos',
            'field' => 'withdraws_filter',
            'value' => 1,
            'cols' => 12
        ])
    </div>
    {!! Form::close() !!}
</div>
{{--{!! HTML::style('assets/portal/css/jquery-ui.css') !!}--}}
{{--{!! HTML::style('assets/portal/css/bet-details.css') !!}--}}

{{--{!! HTML::script('assets/portal/js/moment.min.js') !!}--}}
{!! HTML::script('assets/portal/js/jquery-ui.js') !!}
{!! HTML::script('assets/portal/js/datepicker-pt.js') !!}
{!! HTML::script('assets/portal/js/plugins/jquery-slimscroll/jquery.slimscroll.min.js') !!}

{{--{!! HTML::script(URL::asset('/assets/portal/js/handlebars/handlebars.min.js')) !!}--}}
{{--{!! HTML::script(URL::asset('/assets/portal/js/handlebars/handlebars.custom.js')) !!}--}}

{{--{!! HTML::script(URL::asset('/assets/portal/js/template.js')) !!}--}}

{{--{!! HTML::script(URL::asset('/assets/portal/js/registo/tooltip.js')) !!}--}}
{!! HTML::script(URL::asset('/assets/portal/js/history/operations.js')) !!}
