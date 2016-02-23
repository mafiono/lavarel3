{!! Form::open(array('route' => 'banco/depositar', 'class' => 'form', 'id' => 'saveForm')) !!}
    <div class="registo-form">
        <label>Selecione metodo de Pagamento</label>
        <select class="col-xs-5" name="payment_method">
            <option value="paypal" selected="selected">PayPal</option>
        </select>
    </div>

    <div class="title-form-registo brand-title mini-mtop brand-color aleft">
        » Paypal
    </div>
    <div class="form-box-contend">
        <div class="lin-xs-5">
            <div class="col-xs-3 fleft">
                <label class="col-xs-12" style="line-height:25px;">Valor do Depósito:</label>
            </div>
            <div class="col-xs-4 fleft">
                <input class="col-xs-9" type="text" name="deposit_value" id="deposit_value" />
                <span class="has-error error" style="display:none;"> </span>
            </div>
            <span></span>
            <div class="col-xs-4 fleft">
                <input type="submit" class="col-xs-10 brand-botao brand-link " value="Efetuar Depósito" />
            </div>
            <div class="clear"></div>
        </div>
    </div>
{!! Form::close() !!}