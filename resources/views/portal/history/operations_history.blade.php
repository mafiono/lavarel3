@extends('portal.profile.layout', [
    'active1' => 'historico',
    'middle' => 'portal.history.head_history',
    'active2' => null
])

@section('sub-content')

    <div class="history table-like">
        <div class="row header">
            <div class="col-xs-3">Data</div>
            <div class="col-xs-2 text-center">Origem</div>
            <div class="col-xs-5 text-center">Detalhe</div>
            <div class="col-xs-2 text-right">Valor</div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <div class="place"></div>
            </div>
        </div>
    </div>
@stop