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
                            <td>{{$account->iban}}</td>
                            <td>
                                {!! Form::open(['url' => 'banco/conta-pagamentos/'.$account->id.'/remover', 'method' => 'delete']) !!}
                                    <img src="/assets/portal/img/{{$account->status_id}}.png" alt="{{$account->status->name}}">
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
                <div class="upload">
                    <div id="file_iban" style="cursor:pointer;">
                        <img src="/assets/portal/img/uploadregisto.png"/>
                    </div>
                    <div style="display:none">
                        <input type="File" name="upload" id="upload">
                    </div>
                    <div id="ficheiro" style="color:grey"></div>
                    <span class="has-error error" style="display:none;"> </span>
                </div>
                <div class="profile-button-right">
                    <input type="submit" value="Adicionar"/>
                </div>
                {!! Form::close() !!}
            </div>

            <div id="select-account-container" class="settings-top-margin-normal settings-hidden">
                <h2 class="settings-title">Selecionar Conta de Pagamentos</h2>
                <div class="settings-row">
                    <p class="settings-text">
                        Todos os pedidos de levantamento, depois de aprovados serão efectuados na sua conta de pagamento
                        abaixo indicada.
                        A alteração da conta de pagamento, impossibilita-o de processar levandamento por um periodo de
                        48 horas, necessário
                        para routinas de confirmação de titular.
                    </p>
                </div>
                {!! Form::open(['url' => 'banco/conta-pagamentos', 'method' => 'post']) !!}
                <div class="settings-row">
                    <select id="selected-account" name="selected_account">
                        @foreach($authUser->confirmedBankAccounts as $account)
                            <option value="{{$account->id}}">{{$account->bank_account}} - {{$account->iban}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="settings-row">
                    <input type="submit" class="settings-submit-button fright " value="Alterar"/>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@stop

@section('scripts')
    {!! HTML::script(URL::asset('/assets/portal/js/jquery.validate.js')) !!}
    {!! HTML::script(URL::asset('/assets/portal/js/jquery.validate-additional-methods.js')) !!}
    {!! HTML::script(URL::asset('/assets/portal/js/plugins/jquery-form/jquery.form.min.js')) !!}
    {!! HTML::script(URL::asset('/assets/portal/js/plugins/rx.umd.min.js')) !!}
    {!! HTML::script(URL::asset('/assets/portal/js/bank/accounts.js')) !!}

@stop

