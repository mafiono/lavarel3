{!! Form::open(array('route' => 'banco/depositar', 'class' => 'form deposit', 'id' => 'depositForm')) !!}

    <div class="row icons error-placer no-error">
        <div class="col-xs-6 col-sm-12">
            <div class="choice">
                {!! Form::radio('payment_method', 'cc', null, ['id' => 'method_cc']) !!}
                <label for="method_cc">
                    <img src="/assets/portal/img/thumbs/visa.jpg" alt="" border="0"> Visa
                </label>
                <div class="check"><div class="inside"></div></div>
            </div>
        </div>
        <div class="col-xs-6 col-sm-12">
            <div class="choice">
                {!! Form::radio('payment_method', 'paypal', null, ['id' => 'method_paypal']) !!}
                <label for="method_paypal">
                    <img src="/assets/portal/img/thumbs/paypal.jpg" alt="" border="0"> PayPal
                </label>
                <div class="check"><div class="inside"></div></div>
            </div>
        </div>
        <div class="col-xs-6 col-sm-12">
            <div class="choice">
                {!! Form::radio('payment_method', 'cc', null, ['id' => 'method_mc']) !!}
                <label for="method_mc">
                    <img src="/assets/portal/img/thumbs/mastercard.jpg" alt="" border="0"> MasterCard
                </label>
                <div class="check"><div class="inside"></div></div>
            </div>
        </div>
        <div class="col-xs-6 col-sm-12">
            <div class="choice">
                {!! Form::radio('payment_method', 'mb', null, ['id' => 'method_mb']) !!}
                <label for="method_mb">
                    <img src="/assets/portal/img/thumbs/mb.jpg" alt="" border="0"> Multibanco
                </label>
                <div class="check"><div class="inside"></div></div>
            </div>
        </div>
        <div class="col-xs-6 col-sm-12">
            <div class="choice">
                {!! Form::radio('payment_method', 'meo_wallet', null, ['id' => 'method_meo_wallet']) !!}
                <label for="method_meo_wallet">
                    <img src="/assets/portal/img/thumbs/wallet.jpg" alt="" border="0"> Meo Wallet
                </label>
                <div class="check"><div class="inside"></div></div>
            </div>
        </div>
        <div class="col-xs-6 col-sm-12">
            <div class="choice">
                {!! Form::radio('payment_method', 'bank_transfer', null, ['id' => 'method_bank_transfer']) !!}
                <label for="method_bank_transfer">
                    <img src="/assets/portal/img/thumbs/trans_bank.jpg" alt="" border="0"> Transf. Bancária
                </label>
                <div class="check"><div class="inside"></div></div>
            </div>
        </div>
        <div class="place no-icon"></div>
    </div>
    <div id="deposit_cc" style="display: none;">
        <div class="row">
            <div class="col-xs-6">
                @include('portal.partials.input-text', [
                    'field' => 'cc_name',
                    'name' => 'Nome do cartão',
                    'value' => '',
                ])
            </div>
            <div class="col-xs-6">
                @include('portal.partials.input-text', [
                    'field' => 'cc_nr',
                    'name' => 'Número do cartão',
                    'value' => '',
                ])
            </div>
        </div>
         <div class="row">
            <div class="col-xs-3">
                <?php
                    $meses = ['-' => 'Mês',];
                    $anos = ['-' => 'Ano',];
                    $date = \Carbon\Carbon::now()->day(1)->month(12)->subYear(1);
                    for ($i = 1; $i < 13; $i++) {
                        $meses[$i] = $date->addMonth(1)->format('M');
                    }
                    $base = (int)\Carbon\Carbon::now()->format('Y');
                    for ($i = 1; $i < 10; $i++) {
                        $anos[$base] = $base;
                        $base++;
                    }
                ?>
                @include('portal.partials.input-select', [
                    'field' => 'cc_mes',
                    'name' => 'Validade',
                    'options' => $meses,
                ])
            </div>
            <div class="col-xs-3">
                @include('portal.partials.input-select', [
                    'field' => 'cc_ano',
                    'name' => '&nbsp;',
                    'options' => $anos,
                ])
            </div>
            <div class="col-xs-6">
                @include('portal.partials.input-text', [
                    'field' => 'cc_cvc',
                    'name' => 'CVV2/CVC2',
                    'value' => '',
                ])
            </div>
        </div>
    </div>
    <div id="deposit_area">
        <div class="row amount error-placer">
            <div class="col-xs-6 col-sm-8">
                {!! Form::label('deposit_value', 'Montante que pretende depositar') !!}
            </div>
            <div class="col-xs-6 col-sm-4">
                <div class="input-group">
                    <input id="deposit_value" type="text" class="form-control" name="deposit_value" autocomplete="off">
                </div>
            </div>
            <div class="col-xs-12 place"></div>
        </div>
        <div class="row tax">
            <div class="col-xs-6 col-sm-8">Taxa</div>
            <div class="col-xs-6 col-sm-4"><input type="text" id="tax" disabled="disabled" value="0.00"></div>
        </div>
        <div class="row total">
            <div class="col-xs-6 col-sm-8">Total</div>
            <div class="col-xs-6 col-sm-4"><input type="text" id="total" disabled="disabled" value="0.00"></div>
        </div>

        <input type="submit" value="Depositar" />
    </div>
    <div id="deposit_mb" style="display: none;">
        <div class="row">
            <div class="col-xs-4">
                @include('portal.partials.input-text-disabled', [
                    'field' => 'mb_ent',
                    'name' => 'Entidade',
                ])
            </div>
            <div class="col-xs-4">
                @include('portal.partials.input-text-disabled', [
                    'field' => 'mb_ref',
                    'name' => 'Referência',
                ])
            </div>
            <div class="col-xs-4">
                @include('portal.partials.input-text-disabled', [
                    'field' => 'mb_value',
                    'name' => 'Valor',
                ])
            </div>
            <div class="col-xs-12">
                <div class="texto">
                    Esta referência é válida por 2 semanas e apenas para um pagamento, por favor
                    volte a gerar uma nova referencia sempre que pretender depositar.
                </div>
            </div>
        </div>
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
                    'value' => 'PT50 0036 0076 9910 0069 6998 8',
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