@extends('portal.profile.layout', [
    'active1' => 'banco',
    'middle' => 'portal.bank.head_bank',
    'active2' => 'pagamentos'])

@section('sub-content')

    <div class="row bank">
        <div class="col-xs-12">
            <div class="title">
                Activas
            </div>

            <table class="mini">
                <thead>
                <tr>
                    <th width="25%">Nome</th>
                    <th width="60%">Identificador</th>
                    <th width="15%"></th>
                    <th width="35px"></th>
                </tr>
                </thead>
                <tbody>
                @if (count($user_bank_accounts) >0)
                    @foreach($user_bank_accounts as $account)
                        <tr>
                            <td>{{$account->toName()}}</td>
                            <td>{{$account->toHumanFormat()}}</td>
                            <td>
                                <a href="/perfil/autenticacao/download?id={{$account->user_document_id}}" target="_blank">
                                    <img src="/assets/portal/img/eye.png">
                                </a>
                            </td>
                            <td>@if ($account->canDelete())
                                <a class="remove-account" href="/banco/conta-pagamentos/{{$account->id}}/remover">
                                    <img src="/assets/portal/img/{{$account->status_id}}.png" alt="{{$account->status->name}}">
                                </a>
                                @else
                                    <img src="/assets/portal/img/{{$account->status_id}}.png" alt="{{$account->status->name}}">
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="3">Não tem contas bancarias associadas.</td>
                    </tr>
                @endif
                </tbody>
            </table>
            <div id="add-account-container">
                <div class="title">Adicionar Conta</div>
                {!! Form::open(['url' => 'banco/conta-pagamentos', 'method' => 'put', 'enctype'=> "multipart/form-data", 'id' => 'add-account-form']) !!}

                <div class="row">
                    <div class="col-xs-4">
                        @include('portal.partials.input-text', [
                            'field' => 'bank',
                            'name' => 'Banco',
                            'required' => true,
                        ])
                    </div>
                    <div class="col-xs-8">
                        @include('portal.partials.input-text', [
                            'field' => 'iban',
                            'name' => 'IBAN',
                            'required' => true,
                        ])
                    </div>
                </div>
                <div id="file_iban">
                    @include('portal.partials.input-file', [
                        'field' => 'upload',
                        'name' => 'enviar comprovativo',
                        'autoSubmit' => false,
                    ])
                </div>
                <div class="profile-button-right">
                    <input type="submit" value="Adicionar"/>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@stop