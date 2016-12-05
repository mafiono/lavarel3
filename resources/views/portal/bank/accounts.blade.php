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
                    <th width="30%">Banco</th>
                    <th width="70%">IBAN</th>
                    <th width="35px"></th>
                </tr>
                </thead>
                <tbody>
                @if ($authUser->bankAccounts->count()>0)
                    @foreach($authUser->bankAccounts as $account)
                        <tr>
                            <td>{{$account->bank_account}}</td>
                            <td>{{$account->toHumanFormat()}}</td>
                            <td>
                                {!! Form::open(['url' => 'banco/conta-pagamentos/'.$account->id.'/remover', 'method' => 'delete']) !!}
                                <a class="remove-account" href="#">
                                    <img src="/assets/portal/img/{{$account->status_id}}.png" alt="{{$account->status->name}}">
                                </a>
                                {!! Form::close() !!}
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
            <div id="add-account-container" class="settings-top-margin-normal">
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
                <div id="file_morada">
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

@section('scripts')
    {!! HTML::script(URL::asset('/assets/portal/js/plugins/rx.umd.min.js')) !!}
    {!! HTML::script(URL::asset('/assets/portal/js/bank/accounts.js')) !!}

@stop

