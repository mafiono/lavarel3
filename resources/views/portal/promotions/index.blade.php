@extends('portal.profile.layout', [
    'active1' => 'promocoes',
    'middle' => 'portal.promotions.head_promotions',
    'active2' => 'por_utilizar'])

@section('sub-content')
    @include('portal.promotions.head_menu', ['active' => $tipo ?: 'desportos'])
    <div class="col-xs-9 fleft">
        <div class="title-form-registo brand-title brand-color aleft">
            Promoções e Bónus para utilização
        </div>
        <table class="settings-table">
            <thead>
            <tr>
                <th class="col-xs-3 acenter">Tipo</th>
                <th class="col-xs-5 acenter">Código</th>
                <th class="col-xs-2 acenter">Bónus</th>
                <th class="col-xs-2 acenter">Opções</th>
            </tr>
            </thead>

            <tbody>
            @foreach($availableBonuses as $bonus)
                <tr>
                    <td class="col-xs-3 acenter">{{$bonus->bonusType->name}}
                        <li class="fa fa-info-circle brand-color"></li>
                    </td>
                    <td class="col-xs-5 acenter success-color"><b>{{$bonus->title}}</b></td>
                    <td class="col-xs-2 acenter">{{((float) $bonus->value).($bonus->value_type==='percentage'?'%':'')}}</td>
                    <td class="col-xs-2 acenter neut-back">
                        {{--<button class="brand-botao brand-botao-mini brand-link" data-toggle="modal" data-target="#redeem-modal">Resgatar</button>--}}
                        <a href="/promocoes/redeem/{{$bonus->id}}" class="brand-botao brand-botao-mini brand-link"
                           onclick="return confirmRedeem('{{$bonus->title}}')">Resgatar</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="clear"></div>
@stop

@section('scripts')
    <script>
        function confirmRedeem(title) {return confirm("Tem a certeza que pretende resgatar o " +title+ "?");}
    </script>
@stop