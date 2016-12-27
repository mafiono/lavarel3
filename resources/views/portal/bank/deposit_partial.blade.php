{!! Form::open(array('route' => 'banco/depositar', 'class' => 'form deposit', 'id' => 'saveForm')) !!}

    <div class="row icons">
        <div class="col-xs-6">
            <div class="choice">
                {!! Form::radio('payment_method', 'cc', null, ['id' => 'method_cc']) !!}
                <label for="method_cc">
                    <img src="/assets/portal/img/thumbs/visa.jpg" alt="" border="0"> Visa
                </label>
                <div class="check"><div class="inside"></div></div>
            </div>
        </div>
        <div class="col-xs-6">
            <div class="choice">
                {!! Form::radio('payment_method', 'paypal', null, ['id' => 'method_paypal']) !!}
                <label for="method_paypal">
                    <img src="/assets/portal/img/thumbs/paypal.jpg" alt="" border="0"> Paypal
                </label>
                <div class="check"><div class="inside"></div></div>
            </div>
        </div>
        <div class="col-xs-6">
            <div class="choice">
                {!! Form::radio('payment_method', 'cc', null, ['id' => 'method_mc']) !!}
                <label for="method_mc">
                    <img src="/assets/portal/img/thumbs/mastercard.jpg" alt="" border="0"> MasterCard
                </label>
                <div class="check"><div class="inside"></div></div>
            </div>
        </div>
        <div class="col-xs-6">
            <div class="choice">
                {!! Form::radio('payment_method', 'mb', null, ['id' => 'method_mb']) !!}
                <label for="method_mb">
                    <img src="/assets/portal/img/thumbs/mb.jpg" alt="" border="0"> Multibanco
                </label>
                <div class="check"><div class="inside"></div></div>
            </div>
        </div>
        <div class="col-xs-6">
            <div class="choice">
                {!! Form::radio('payment_method', 'meo_wallet', null, ['id' => 'method_meo_wallet']) !!}
                <label for="method_meo_wallet">
                    <img src="/assets/portal/img/thumbs/wallet.jpg" alt="" border="0"> Meo Wallet
                </label>
                <div class="check"><div class="inside"></div></div>
            </div>
        </div>
        <div class="col-xs-6">
            <div class="choice">
                {!! Form::radio('payment_method', 'bank_transfer', null, ['id' => 'method_bank_transfer']) !!}
                <label for="method_bank_transfer">
                    <img src="/assets/portal/img/thumbs/trans_bank.jpg" alt="" border="0"> Transf. Bancária
                </label>
                <div class="check"><div class="inside"></div></div>
            </div>
        </div>
    </div>
    <div id="deposit_area">
        <div class="form-group row amount">
            <div class="col-xs-7">
                {!! Form::label('deposit_value', 'montante que pretende depositar') !!}
            </div>
            <div class="col-xs-5">
                <div class="input-group">
                    <input id="deposit_value" type="number" class="form-control" name="deposit_value">
                    <span class="has-error error" style="display:none;"> </span>
                </div>
            </div>
        </div>
        <div class="row tax">
            <div class="col-xs-6">Taxa</div>
            <div class="col-xs-6"><input type="text" id="tax" disabled="disabled" value="0.00"></div>
        </div>
        <div class="row total">
            <div class="col-xs-6">Total</div>
            <div class="col-xs-6"><input type="text" id="total" disabled="disabled" value="0.00"></div>
        </div>

        <input type="submit" value="Depositar" />
    </div>
    <div id="deposit_tb" style="display: none;">
        <div class="row">
            <div class="col-xs-8">
                @include('portal.partials.input-text-disabled', [
                    'field' => 'nome',
                    'name' => 'Entidade',
                    'value' => 'Sociedade Figueira Praia, SA',
                ])
            </div>
            <div class="col-xs-4">
                @include('portal.partials.input-text-disabled', [
                    'field' => 'nome',
                    'name' => 'Banco',
                    'value' => 'Montepio Geral',
                ])
            </div>
            <div class="col-xs-8">
                @include('portal.partials.input-text-disabled', [
                    'field' => 'nome',
                    'name' => 'IBAN',
                    'value' => 'PT50 0036 0076 9910 0063 5937 3',
                ])
            </div>
            <div class="col-xs-4">
                @include('portal.partials.input-text-disabled', [
                    'field' => 'nome',
                    'name' => 'BIC/SWIFT',
                    'value' => 'MPIOPTPL',
                ])
            </div>
            <div class="col-xs-12">
                <div class="texto">
                    Este depósito poderá demorar até 3 dias úteis. Introduza o seu Id de jogador na descrição da transfêrencia.
                    O Seu ID é  {{$authUser->id}}.
                </div>
            </div>
        </div>
    </div>
{!! Form::close() !!}