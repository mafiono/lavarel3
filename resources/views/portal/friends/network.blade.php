@extends('portal.profile.layout', [
    'active1' => 'convidar',
    'middle' => 'portal.friends.head_friends',
    'active2' => 'rede'])

@section('sub-content')

<div class="col-xs-12 lin-xs-11 fleft">
    <div class="box-form-user-info lin-xs-12">
        <div class="title-form-registo brand-title brand-color aleft">
            Rede de Amigos Registados
        </div>

        <div class="table_user mini-mbottom">
            <table class="col-xs-12">
                <thead>
                    <tr>
                        <th class="col-xs-2">Data</th>
                        <th class="col-xs-4">Email</th>
                        <th class="col-xs-2">Apostas Realizadas</th>
                        <th class="col-xs-2">Apostas Válidas</th>
                        <th class="col-xs-2">Bónus Recebidos</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="col-xs-2 neut-color">2015-09-01</td>
                        <td class="col-xs-4 neut-color">example@gmail.com</td>
                        <td class="col-xs-2 neut-color acenter">88</td>
                        <td class="col-xs-2 neut-color acenter">22</td>
                        <td class="col-xs-2 neut-color acenter">0,00</td>
                    </tr>
                    <tr>
                        <td class="col-xs-2 neut-color">2015-09-01</td>
                        <td class="col-xs-4 neut-color">example@gmail.com</td>
                        <td class="col-xs-2 neut-color acenter">87</td>
                        <td class="col-xs-2 neut-color acenter">25</td>
                        <td class="col-xs-2 neut-color acenter">10,00</td>
                    </tr>
                    <tr>
                        <td class="col-xs-2 neut-color">2015-09-01</td>
                        <td class="col-xs-4 neut-color">example@gmail.com</td>
                        <td class="col-xs-2 neut-color acenter">86</td>
                        <td class="col-xs-2 neut-color acenter">24</td>
                        <td class="col-xs-2 neut-color acenter">20,00</td>
                    </tr>
                    <tr>
                        <td class="col-xs-2 neut-color">2015-09-01</td>
                        <td class="col-xs-4 neut-color">example@gmail.com</td>
                        <td class="col-xs-2 neut-color acenter">85</td>
                        <td class="col-xs-2 neut-color acenter">23</td>
                        <td class="col-xs-2 neut-color acenter">30,00</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@stop
