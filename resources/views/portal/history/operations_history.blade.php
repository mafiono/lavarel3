@extends('layouts.portal')

@section('styles')

    <style>
        .settings-col,
        .settings-table {
            width: 590px;
        }

        .settings-table th:nth-child(1), .settings-table td:nth-child(1) {
            text-align: left;
            width: 100px;
            max-width: 100px;
            min-width: 100px;
        }
        .settings-table th:nth-child(2), .settings-table td:nth-child(2) {
            text-align: left;
            width: 230px;
            max-width: 230px;
            min-width: 230px;

            overflow-x: hidden;
        }

        .settings-table th:nth-child(3), .settings-table th:nth-child(4),
        .settings-table td:nth-child(3), .settings-table td:nth-child(4) {
            width: 80px;
            min-width: 80px;
            max-width: 80px;
        }
        .settings-table th:nth-child(5), .settings-table td:nth-child(5)  {
            width: 60px;
            min-width: 60px;
            max-width: 60px;
        }
    </style>
@stop


@section('content')
    @include('portal.profile.head', ['active' => 'HISTÓRICO'])
    @include('portal.history.head_history', ['active' => 'HISTORICO OPERACOES', 'input' => $input])

    <div class="settings-col">

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
            <tbody id="operations-history-container">
            </tbody>
        </table>

    </div>

    @include('portal.profile.bottom')
@stop

@section('scripts')
@stop