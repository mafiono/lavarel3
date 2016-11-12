@extends('portal.profile.layout', [
    'active1' => 'banco',
    'middle' => 'portal.bank.head_bank',
    'active2' => 'pagamentos'])



@section('sub-content')

    <div class="col-xs-12">
        <table class="settings-table">
            <thead>
            <tr>
                <th>Banco</th>
                <th>IBAN</th>
                <th>Estado</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @if ($authUser->bankAccounts->count()>0)
                @foreach($authUser->bankAccounts as $account)
                <tr>
                    <td>{{$account->bank_account}}</td>
                    <td>{{$account->iban}}</td>
                    <td>{{$account->status->name}}</td>
                    <td>
                        {!! Form::open(['url' => 'banco/conta-pagamentos/'.$account->id.'/remover', 'method' => 'delete']) !!}
                            <button class="fa fa-times brand-color remove-account" alt="Apagar" title="Apagar?"></button>
                        {!! Form::close() !!}
                    </td>
                </tr>
                @endforeach
            @else
                <tr>
                    <td></td>
                    <td>Não tem contas bancarias associadas.</td>
                    <td></td>
                    <td></td>
                </tr>
            @endif
            </tbody>
        </table>
            @if ($authUser->confirmedBankAccounts->count()>0)
            <div class="settings-row settings-buttons settings-top-margin-small aright">
                <button id="add-account-btn" class="settings-button settings-button-selected">Adicionar Conta</button>
                <button id="select-account-btn" class="settings-button">Selecionar Conta</button>
            </div>
            @endif

            <div id="add-account-container" class="settings-top-margin-normal">
                <h2 class="settings-title">Adicionar Conta de Pagamentos</h2>
                {!! Form::open(['url' => 'banco/conta-pagamentos', 'method' => 'put', 'enctype'=> "multipart/form-data", 'id' => 'add-account-form']) !!}
                <div class="settings-row">
                    <label for="banco">Banco</label>
                    <input class="settings-textbox" type="text" id="bank" name="bank" placeholder="Banco" required>
                </div>
                <div class="settings-row">
                    <label for="iban">IBAN</label>
                    <span class="prefix">PT50</span><input class="settings-textbox with-prefix" type="text" id="iban" name="iban" placeholder="IBAN" required>
                    <span class="has-error error"></span>
                </div>
                <div class="settings-row">
                    <label for="upload">Comprovativo</label>
                    <input type="file" id="upload" name="upload" class="required brand-botao brand-link settings-textbox" />
                    <span class="has-error error"></span>
                </div>
                <div class="settings-row">
                    <input type="submit" class="settings-submit-button fright " value="Adicionar" />
                </div>
                {!! Form::close() !!}
            </div>

            <div id="select-account-container" class="settings-top-margin-normal settings-hidden">
                <h2 class="settings-title">Selecionar Conta de Pagamentos</h2>
                <div class="settings-row">
                    <p class="settings-text">
                        Todos os pedidos de levantamento, depois de aprovados serão efectuados na sua conta de pagamento abaixo indicada.
                        A alteração da conta de pagamento, impossibilita-o de processar levandamento por um periodo de 48 horas, necessário
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
                    <input type="submit" class="settings-submit-button fright " value="Alterar" />
                </div>
                {!! Form::close() !!}
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

