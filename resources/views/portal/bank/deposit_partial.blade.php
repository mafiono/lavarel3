{!! Form::open(array('route' => 'banco/depositar', 'class' => 'form', 'id' => 'saveForm')) !!}

<div class="row icons">
    <div class="col-xs-6">
        <input type="radio" name="payment_method" id="method_cc" value="cc" checked="checked">
        <label for="method_cc">
            <img src="/assets/portal/img/thumbs/visa.jpg" alt="" border="0"> Visa
        </label>
    </div>
    <div class="col-xs-6">
        <input type="radio" name="payment_method" id="method_paypal" value="paypal">
        <label for="method_paypal">
            <img src="/assets/portal/img/thumbs/paypal.jpg" alt="" border="0"> Paypal
        </label>
    </div>
    <div class="col-xs-6">
        <input type="radio" name="payment_method" id="method_mc" value="cc">
        <label for="method_mc">
            <img src="/assets/portal/img/thumbs/mastercard.jpg" alt="" border="0"> MasterCard
        </label>
    </div>
    <div class="col-xs-6">
        <input type="radio" name="payment_method" id="method_mb" value="mb">
        <label for="method_mb">
            <img src="/assets/portal/img/thumbs/mb.jpg" alt="" border="0"> Multibanco
        </label>
    </div>
    <div class="col-xs-6">
        <input type="radio" name="payment_method" id="method_meo_wallet" value="meo_wallet">
        <label for="method_meo_wallet">
            <img src="/assets/portal/img/thumbs/wallet.jpg" alt="" border="0"> Meo Wallet
        </label>
    </div>
    <div class="col-xs-6">
        <input type="radio" name="payment_method" id="method_tb" value="tb">
        <label for="method_tb">
            <img src="/assets/portal/img/thumbs/trans_bank.jpg" alt="" border="0"> Transf. Bancária
        </label>
    </div>
</div>

            <input style="margin-right:30px;margin-top:70px" type="submit"  value="Confirmar" />




{!! Form::close() !!}