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
            <th class="col-4">Email</th>
            <th class="col-4">Apostas Válidas</th>
            <th class="col-2">Bónus</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td class="col-2 neut-color">2015-09-01</td>
            <td class="col-4 neut-color">example@gmail.com</td>
            <td class="col-4 neut-color acenter">
                <div class="progress">
                    <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%;">
                        <span class="sr-only">60% Complete</span>
                    </div>
                </div>
            </td>
            <td class="col-2 neut-color acenter">0,00</td>
        </tr>
        <tr>
            <td class="col-2 neut-color">2015-09-01</td>
            <td class="col-4 neut-color">example@gmail.com</td>
            <td class="col-4 neut-color acenter">
                <div class="progress">
                    <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%;">
                        <span class="sr-only">60% Complete</span>
                    </div>
                </div>
            </td>
            <td class="col-2 neut-color acenter">10,00</td>
        </tr>
        <tr>
            <td class="col-2 neut-color">2015-09-01</td>
            <td class="col-4 neut-color">example@gmail.com</td>
            <td class="col-4 neut-color acenter">
                <div class="progress">
                    <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%;">
                        <span class="sr-only">60% Complete</span>
                    </div>
                </div>
            </td>
            <td class="col-2 neut-color acenter">20,00</td>
        </tr>
        <tr>
            <td class="col-2 neut-color">2015-09-01</td>
            <td class="col-4 neut-color">example@gmail.com</td>
            <td class="col-4 neut-color acenter">
                <div class="progress">
                    <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%;">
                        <span class="sr-only">60% Complete</span>
                    </div>
                </div>
            </td>
            <td class="col-2 neut-color acenter">30,00</td>
        </tr>
        </tbody>
    </table>
</div>
@stop
