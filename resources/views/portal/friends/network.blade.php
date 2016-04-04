@extends('portal.profile.layout', [
    'active1' => 'convidar',
    'middle' => 'portal.friends.head_friends',
    'active2' => 'rede'])

@section('sub-content')

<div class="col-xs-12 fleft">
    <div class="title-form-registo brand-title brand-color aleft">
        Rede de Amigos Registados
    </div>

    <table class="settings-table">
        <thead>
        <tr>
            <th class="col-2">Data</th>
            <th class="col-3">Email</th>
            <th class="col-3">Apostas Válidas</th>
            <th class="col-2">Bónus</th>
            <th class="col-2">Estado</th>
        </tr>
        </thead>
        <tbody>
        <?php
        /** @var $friend \App\Models\UserInvite */
        ?>
        @foreach($friends as $friend)
            <tr>
                <td class="col-2">{{$friend->regist_date->format('Y-m-d')}}</td>
                <td class="col-3">{{$friend->email}}</td>
                <?php
                    $val = max(min($friend->bet_sum, 100), 0);
                ?>
                <td class="col-3 acenter">
                    <div class="progress">
                        <div class="progress-bar" role="progressbar" aria-valuenow="{{$val}}" aria-valuemin="0" aria-valuemax="100" style="width: {{$val}}%;">
                            <span class="sr-only">{{$val}}% Complete</span>
                        </div>
                    </div>
                </td>
                <td class="col-2 acenter">10,00 €</td>
                <td class="col-2 acenter">{{$friend->status_id}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@stop
