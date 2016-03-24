@extends('portal.profile.layout', [
    'active1' => 'historico',
    'middle' => 'portal.history.head_history',
    'active2' => 'depositos'])

@section('sub-content')
    <style>
        .settings-table th:nth-child(1) {
            text-align: left;
            width: 100px;
        }
        .settings-table th:nth-child(2), .settings-table th:nth-child(3) {
            text-align: center;
            width: 170px;
        }
        .settings-table th:nth-child(4) {
            width: 100px;
        }

        .settings-table td:nth-child(1) {
            text-align: left;
        }
        .settings-table td:nth-child(1), .settings-table td:nth-child(4) {
            width: 100px;
        }
        .settings-table td:nth-child(2), .settings-table td:nth-child(3) {
            text-overflow: clip;
            width: 170px;
            max-width: 170px;
        }

    </style>

    <div class="settings-col">
        <table class="settings-table">
            <thead>
                <tr>
                    <th>DATA</th>
                    <th>REFERÊNCIA</th>
                    <th>CONTA</th>
                    <th>MONTANTE</th>
                </tr>
            </thead>
            <tbody>
            @foreach($user_deposits as $deposit)
                <tr>
                    <td>{{$deposit->created_at}}</td>
                    <td class="settings-text-darker">{{$deposit->transaction_id}}</td>
                    <td class="settings-text-darker">{{$deposit->user_bank_account_id}}</td>
                    <td>{{$deposit->credit}} €</td>
                </tr>
            @endforeach
                <tr>
                    <td>2015-12-15 </td>
                    <td>1668443776</td>
                    <td>PT50123443211234567890172</td>
                    <td>100 €</td>
                </tr>
            </tbody>
        </table>
    </div>

@stop

@section('scripts')
@stop