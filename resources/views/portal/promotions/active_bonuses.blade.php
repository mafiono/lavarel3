@extends('portal.profile.layout', [
    'active1' => 'promocoes',
    'middle' => 'portal.promotions.head_promotions',
    'active2' => 'activos'])

@section('sub-content')
<div class="col-xs-12 lin-xs-11 fleft">
    <div class="box-form-user-info lin-xs-12">
        <div class="title-form-registo brand-title brand-color aleft">
            Promoções e Bónus pendentes
        </div>
        @include('portal.messages')
        <div class="table_user mini-mbottom">
            <table class="col-xs-12">
                <thead>
                    <tr>
                        <th class="col-xs-2 acenter">Origem</th>
                        <th class="col-xs-4 acenter">Tipo</th>
                        <th class="col-xs-2 acenter">Bónus</th>
                        <th class="col-xs-2 acenter">Código</th>
                        <th class="col-xs-2 acenter">Opções</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($activeBonuses as $activeBonus)
                    <tr>
                        <td class="col-xs-2 acenter">Desporto</td>
                        <td class="col-xs-4 acenter">{{$activeBonus->bonus->bonusType->name}} <li class="fa fa-info-circle brand-color"></li></td>
                        <td class="col-xs-2 acenter">{{((float) $activeBonus->bonus->value).($activeBonus->bonus->value_type==='percentage'?'%':'')}}</td>
                        <td class="col-xs-2 acenter success-color"><b>{{$activeBonus->bonus->title}}</b></td>
                        <td class="col-xs-2 acenter neut-back"><a href="/promocoes/cancel/{{$activeBonus->id}}" class="brand-botao brand-botao-mini brand-link" onclick="return confirmCancel('{{$activeBonus->bonus->title}}')">Cancelar</a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@stop

@section('scripts')
    <script>
        function confirmCancel(title) {return confirm("Tem a certeza que pretende cancelar o " +title+ "?");}
    </script>
@stop