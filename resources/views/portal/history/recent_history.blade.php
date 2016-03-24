@extends('layouts.portal', ['mini' => true])

@section('content')
    @include('portal.profile.head', ['active' => 'HISTÓRICO'])
    @include('portal.history.head_history', ['active' => 'APOSTAS RECENTES'])

    <style>
        .settings-col {
            width: 550px;
        }

        .settings-table th:nth-child(1), .settings-table td:nth-child(1) {
            text-align: left;
            width: 100px;
            max-width: 100px;
            min-width: 100px;
        }
        .settings-table th:nth-child(2) {
            text-align: left;
            padding-left: 10px;
            width: 130px;
            min-width: 130px;
            max-width: 130px;
        }
        .settings-table td:nth-child(2) {
            text-align: left;
            width: 140px;
            max-width: 140px;
            min-width: 140px;
        }

        .settings-table th:nth-child(3), .settings-table th:nth-child(4),
        .settings-table td:nth-child(3), .settings-table td:nth-child(4) {
            width: 90px;
            min-width: 90px;
            max-width: 90px;
        }
        .settings-table th:nth-child(5), .settings-table td:nth-child(5)  {
            width: 70px;
            min-width: 70px;
            max-width: 70px;
        }
    </style>

    <div class="settings-col">
        <table class="settings-table">
            <thead>
                <tr>
                    <th>DATA</th>
                    <th>TIPO</th>
                    <th>CRÉDITO</th>
                    <th>DEBITO</th>
                    <th>TAXA</th>
                </tr>
            </thead>
            <tbody>
            @foreach($user_bets as $bet)
            <tr>
                <td>{{$bet->created_at->format('d/m/y H:i')}}</td>
                <td class="settings-text-darker">Aposta nº{{$bet->api_bet_id}}</td>
                <td>{{$bet->result_amount*1?($bet->result_amount/100)." €":""}}</td>
                <td>{{$bet->amount*1?$bet->amount." €":""}}</td>
                <td>0 €</td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@stop

@section('scripts')
@stop