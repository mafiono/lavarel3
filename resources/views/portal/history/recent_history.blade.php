@extends('layouts.portal', ['mini' => true])

@section('content')
    @include('portal.profile.head', ['active' => 'HISTÓRICO'])
    @include('portal.history.head_history', ['active' => 'APOSTAS RECENTES'])

    <div class="bs-wp">

        <table class="table table-striped" style="width:90%;color:blue">
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