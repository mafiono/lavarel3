@extends('portal.profile.layout', [
    'active1' => 'promocoes',
    'middle' => 'portal.promotions.head_promotions',
    'active2' => 'por_utilizar'])

@section('sub-content')
    <div id="redeem-modal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Resgate do bonus</h4>
                </div>
                <div class="modal-body">
                    <p>Tem a certeza que pretende resgatar o bonus.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary">Resgatar</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <div class="col-xs-12 fleft">
        <div class="title-form-registo brand-title brand-color aleft">
            Promoções e Bónus para utilização
        </div>
        @include('portal.messages')
        <table class="settings-table">
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
                @foreach($availableBonuses as $bonus)
                <tr>
                    <td class="col-xs-2 acenter">Desporto</td>
                    <td class="col-xs-4 acenter">{{$bonus->bonus_type}} <li class="fa fa-info-circle brand-color"></li></td>
                    <td class="col-xs-2 acenter">{{$bonus->value}}</td>
                    <td class="col-xs-2 acenter success-color"><b>{{$bonus->title}}</b></td>
                    <td class="col-xs-2 acenter neut-back">
                        {{--<button class="brand-botao brand-botao-mini brand-link" data-toggle="modal" data-target="#redeem-modal">Resgatar</button>--}}
                        <a href="/promocoes/redeem/{{$bonus->id}}" class="brand-botao brand-botao-mini brand-link" onclick="return confirmRedeem('{{$bonus->title}}')">Resgatar</a>
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