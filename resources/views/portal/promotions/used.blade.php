@extends('layouts.portal', ['mini' => true])

@section('content')

        @include('portal.profile.head', ['active' => 'PROMOÇÕES'])

        @include('portal.promotions.head_promotions', ['active' => 'Utilizados'])

            <div class="col-xs-7 lin-xs-11 fleft">
                <div class="box-form-user-info lin-xs-12">
                    <div class="title-form-registo brand-title brand-color aleft">
                        Promoções e Bónus utilizados
                    </div>
                    
                    <div class="table_user mini-mbottom">
                        <table class="col-xs-12">
                            <thead>
                                <tr>
                                    <th class="col-xs-3 acenter">Origem</th>
                                    <th class="col-xs-4 acenter">Tipo</th>
                                    <th class="col-xs-2 acenter">Bónus</th>
                                    <th class="col-xs-3 acenter">Data Util.</th>
                                </tr>
                            </thead>
                            
                            <tbody>
                                <tr>
                                    <td class="col-xs-3 acenter">Desporto</td>
                                    <td class="col-xs-4 acenter">1º Depósito <li class="fa fa-info-circle brand-color"></li></td>
                                    <td class="col-xs-2 acenter">200%</td>
                                    <td class="col-xs-3 acenter"><b>15-07-2015</b></td>
                                </tr>
                                <tr>
                                    <td class="col-xs-3 acenter">Desporto</td>
                                    <td class="col-xs-4 acenter">Bónus Fidelidade <li class="fa fa-info-circle brand-color"></li></td>
                                    <td class="col-xs-2 acenter">50%</td>
                                    <td class="col-xs-3 acenter"><b>15-07-2015</b></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        @include('portal.profile.bottom')
                        
@stop

@section('scripts')


@stop