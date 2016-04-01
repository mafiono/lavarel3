@extends('portal.profile.layout', [
    'active1' => 'promocoes',
    'middle' => 'portal.promotions.head_promotions',
    'active2' => 'activos'])

@section('sub-content')
<div class="col-xs-12 fleft">
    <div class="title-form-registo brand-title brand-color aleft">
        Promoções e Bónus pendentes
    </div>
    @include('portal.messages')

    <table class="settings-table">
        <thead>
            <tr>
                <th class="col-2 acenter">Origem</th>
                <th class="col-4 acenter">Tipo</th>
                <th class="col-2 acenter">Bónus</th>
                <th class="col-2 acenter">Código</th>
                <th class="col-2 acenter">Opções</th>
            </tr>
        </thead>

        <tbody>
            @foreach($activeBonuses as $bonus)
            <tr>
                <td class="col-2 acenter">Desporto</td>
                <td class="col-4 acenter">{{$bonus->bonusType->name}} <li class="fa fa-info-circle brand-color"></li></td>
                <td class="col-2 acenter">{{$bonus->value}}</td>
                <td class="col-2 acenter success-color"><b>{{$bonus->title}}</b></td>
                <td class="col-2 acenter neut-back"><a href="/promocoes/cancel/{{$bonus->id}}" class="brand-botao brand-botao-mini brand-link" onclick="return confirmCancel('{{$bonus->title}}')">Cancelar</a></td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@stop

@section('scripts')
    <script>
        function confirmCancel(title) {return confirm("Tem a certeza que pretende cancelar o " +title+ "?");}
    </script>
@stop