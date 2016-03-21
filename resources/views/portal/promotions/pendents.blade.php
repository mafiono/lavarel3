@extends('portal.profile.layout', [
    'active1' => 'PROMOÇÕES',
    'middle' => 'portal.promotions.head_promotions',
    'active2' => 'Pendentes'])

@section('sub-content')
<div class="col-xs-12 lin-xs-11 fleft">
    <div class="box-form-user-info lin-xs-12">
        <div class="title-form-registo brand-title brand-color aleft">
            Promoções e Bónus pendentes
        </div>

        <div class="table_user mini-mbottom">
            <table class="col-xs-12">
                <thead>
                    <tr>
                        <th class="col-xs-2 acenter">Origem</th>
                        <th class="col-xs-4 acenter">Tipo</th>
                        <th class="col-xs-2 acenter">Bónus</th>
                        <th class="col-xs-4 acenter">Estado</th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <td class="col-xs-2 acenter">Desporto</td>
                        <td class="col-xs-4 acenter">1º Depósito <li class="fa fa-info-circle brand-color"></li></td>
                        <td class="col-xs-2 acenter">200%</td>
                        <td class="col-xs-2 acenter"><b>Aguardar Depósito</b></td>
                    </tr>
                    <tr>
                        <td class="col-xs-2 acenter">Desporto</td>
                        <td class="col-xs-4 acenter">Bónus Fidelidade <li class="fa fa-info-circle brand-color"></li></td>
                        <td class="col-xs-2 acenter">50%</td>
                        <td class="col-xs-2 acenter"><b>Em Aprovação</b></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@stop

@section('scripts')


@stop