@extends('layouts.portal', ['mini' => true])

@section('content')
    @include('portal.profile.head', ['active' => 'HISTÓRICO'])
    @include('portal.history.head_history', ['active' => 'LEVANTAMENTOS'])

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
            @foreach($user_withdrawals as $withdrawal)
                <tr>
                    <td>{{$withdrawal->created_at}}</td>
                    <td class="settings-text-darker">{{$withdrawal->transaction_id}}</td>
                    <td class="settings-text-darker">{{$withdrawal->user_bank_account_id}}</td>
                    <td>{{$withdrawal->charge}} €</td>
                </tr>
            @endforeach
            <tr>
                <td>2015-12-13 </td>
                <td>2568443771</td>
                <td>PT50123443211234567890172</td>
                <td>57 €</td>
            </tr>
            </tbody>
        </table>
    </div>

    @include('portal.profile.bottom')
@stop

@section('scripts')
@stop