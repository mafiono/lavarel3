@extends('portal.profile.layout', [
    'active1' => 'historico',
    'middle' => 'portal.history.head_history',
    'active2' => 'operacoes',
    'input' => $input])

@section('sub-content')

    <div class="bs-wp">
    <table class="table table-striped" style="width:90%;color:#4a7fb3">
        <thead>
        <tr>
                    <th >Data</th>
                    <th >Tipo</th>
                    <th >Detalhes</th>
                    <th >Valor</th>
                </tr>
            </thead>
        </table>
        <div id="operations-history-container" style="width:100%">
            <table class="table table-striped" style="width:75%;color:#4a7fb3">
                <tbody></tbody>
            </table>
        </div>
    </div>


@stop