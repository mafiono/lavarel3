@extends('portal.profile.layout', [
    'active1' => 'banco',
    'middle' => 'portal.bank.head_bank',
    'active2' => 'saldo'])

@section('sub-content')

    <div class="col-xs-12 fleft">

        @include('portal.messages')
        <div class="left">
        <div class="title">
            Saldo (EUR)
        </div>
        </div>
        <div class="profile-table">
            <table>
                <thead>
                    <tr>
                        <th style="text-align: left">Disponível</th>
                        <th style="text-align: left">Contabilistico</th>
                        <th style="text-align: right">Bónus</th>
                        <th style="text-align: right">Total</th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <td >{{ $authUser->balance->balance_available }}</td>
                        <td >{{ $authUser->balance->balance_accounting }}</td>
                        <td style="text-align: right" >{{ $authUser->balance->balance_bonus }}</td>
                        <td style="text-align: right"><b>{{ $authUser->balance->balance_total }}</b></td>
                    </tr>
                </tbody>
            </table>

        </div>

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
                        <td >Desportos</td>
                        <td style="text-align:right" ><b>0.00</b></td>
                    </tr>
                    <tr>
                        <td >Casino</td>
                        <td style="text-align:right"><b>0.00</b></td>
                    </tr>
                    <tr>
                        <td >Póker</td>
                        <td style="text-align:right"><b>0.00</b></td>
                    </tr>
                    <tr>
                        <td >Jogos/Vegas</td>
                        <td style="text-align:right"><b>0.00</b></td>
                    </tr>
                </tbody>
            </table>

        </div>
    </div>
    <div class="clear"></div>

@stop

@section('scripts')

{!! HTML::script(URL::asset('/assets/portal/js/plugins/jquery-form/jquery.form.min.js')); !!}
{!! HTML::script(URL::asset('/assets/portal/js/forms.js')); !!}

@stop

