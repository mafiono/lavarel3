@extends('portal.profile.layout', [
    'active1' => 'historico',
    'middle' => 'portal.history.head_history',
    'active2' => 'operacoes',
    'input' => $input])

@section('styles')

    <style>
        .bet .settings-col,
        .bet .settings-table {
            width: 590px;
        }

        .bet .settings-table th:nth-child(1), .bet .settings-table td:nth-child(1) {
            text-align: left;
            width: 100px;
            max-width: 100px;
            min-width: 100px;
        }
        .bet .settings-table th:nth-child(2), .bet .settings-table td:nth-child(2) {
            text-align: left;
            width: 230px;
            max-width: 230px;
            min-width: 230px;

            overflow-x: hidden;
        }

        .bet .settings-table th:nth-child(3), .bet .settings-table th:nth-child(4),
        .bet .settings-table td:nth-child(3), .bet .settings-table td:nth-child(4) {
            width: 80px;
            min-width: 80px;
            max-width: 80px;
        }
        .bet .settings-table th:nth-child(5), .bet .settings-table td:nth-child(5)  {
            width: 60px;
            min-width: 60px;
            max-width: 60px;
        }
    </style>
@stop

@section('sub-content')
    <div class="col-xs-12">
        <table class="settings-table">
            <thead>
                <tr>
                    <th>DATA</th>
                    <th>TIPO</th>
                    <th>CRÉDIDO</th>
                    <th>DÉBITO</th>
                    <th>TAXA</th>
                </tr>
            </thead>
        </table>

        <table class="settings-table">
            <tbody id="operations-history-container">
            </tbody>
        </table>
    </div>

@stop

@section('scripts')
@stop