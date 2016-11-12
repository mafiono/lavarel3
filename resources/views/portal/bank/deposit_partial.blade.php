{!! Form::open(array('route' => 'banco/depositar', 'class' => 'form', 'id' => 'saveForm')) !!}

    <select style="margin-left:0px;float:left;width:300px;" name="payment_method">
        <option value="paypal" selected="selected">PayPal</option>
        <option value="meowallet">Meo Wallet</option>
    </select>


@include('portal.messages')




            <input style=" margin-left:30px; width:100px;" type="text" name="deposit_value" id="deposit_value" />
            <span class="has-error error" style="display:none;"> </span>



            <input style="margin-right:30px;margin-top:70px" type="submit"  value="Confirmar" />




{!! Form::close() !!}