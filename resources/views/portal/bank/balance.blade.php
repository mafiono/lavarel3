@extends('portal.profile.layout', [
    'active1' => 'banco',
    'middle' => 'portal.bank.head_bank',
    'active2' => 'saldo'])

@section('sub-content')

    <div class="row">
        @include('portal.bank.mini_balance')
        <div class="col-xs-12">

            <div style="margin-top:60px" class="left">
                <div class="title">Bónus Activos (EUR)</div>
            </div>

            <div class="profile-2table">
                <table>
                    <thead>
                    <tr>
                        <th style="text-align:left">Tipo</th>
                        <th style="text-align:right">Montante</th>
                    </tr>
                    </thead>

                    <tbody>
                    <tr>
                        <td>Desportos</td>
                        <td style="text-align:right"><b>0.00</b></td>
                    </tr>
                    <tr>
                        <td>Casino</td>
                        <td style="text-align:right"><b>0.00</b></td>
                    </tr>
                    </tbody>
                </table>

            </div>
        </div>
    </div>
@stop

