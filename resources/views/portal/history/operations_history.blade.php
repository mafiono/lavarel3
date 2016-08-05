@extends('portal.profile.layout', [
    'active1' => 'historico',
    'middle' => 'portal.history.head_history',
    'active2' => 'operacoes',
    'input' => $input])

@section('sub-content')
    <link media="all" type="text/css" rel="stylesheet" href="/assets/portal/css/bet-details.css">
    <div class="col-xs-12">
        <table class="settings-table">
            <thead>
                <tr>
                    <th class="col-2">Data</th>
                    <th class="col-3">Tipo</th>
                    <th class="col-5">Detalhes</th>
                    <th class="col-2">Valor</th>
                </tr>
            </thead>
        </table>
        <div id="operations-history-container">
            <table class="settings-table">
                <tbody></tbody>
            </table>
        </div>
    </div>

@stop