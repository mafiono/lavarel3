@extends('portal.profile.layout', [
    'active1' => 'promocoes',
    'middle' => 'portal.promotions.head_promotions',
    'active2' => 'utilizados'])

@section('sub-content')

<div class="col-xs-12 lin-xs-11 fleft">
    <div class="box-form-user-info lin-xs-12">
        <div class="title-form-registo brand-title brand-color aleft" style="padding-bottom: 0;">
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
                    @foreach($consumedSportBonuses as $consumedBonus)
                    <tr>
                        <td class="col-xs-3 acenter">Desporto</td>
                        <td class="col-xs-4 acenter">{{$consumedBonus->bonus->bonusType->name}} <li class="fa fa-info-circle brand-color"></li></td>
                        <td class="col-xs-2 acenter">{{((float) $consumedBonus->bonus->value).($consumedBonus->bonus->value_type==='percentage'?'%':'')}}</td>
                        <td class="col-xs-3 acenter">{{$consumedBonus->updated_at->format('Y-m-d')}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@stop

@section('scripts')


@stop