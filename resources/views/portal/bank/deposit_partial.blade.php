<style>
    .depositselect{
        width:40%;
        float:left;
    }
    .amount{
    width:60%;
    float:left;
    }
</style>


{!! Form::open(array('route' => 'banco/depositar', 'class' => 'form', 'id' => 'saveForm')) !!}


<div class="row">
    <div class="depositselect">
        <div class="registo-form">
            <label>Selecione metodo de Pagamento</label>
            <select class="col-xs-5" name="payment_method">
                <option value="paypal" selected="selected">PayPal</option>
                <option value="meowallet">Meo Wallet</option>
            </select>
        </div></div>
    <div class="amount">

        <label  style="line-height:25px;">Valor do Depósito:</label>

        <div class="col-xs-4 fleft">
            <input class="col-xs-9" type="text" name="deposit_value" id="deposit_value" />
            <span class="has-error error" style="display:none;"> </span>
        </div>
        <span></span>
        <div class="actions" style="margin-bottom:10px;">
            <button type="submit" id="concluir" class="submit">CONCLUIR</button>

            <button id="limpar">Depositar</button>
        </div></div>
</div>
</div>


{!! Form::close() !!}