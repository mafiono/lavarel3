<div class="clearfix hidden" id="reset_pass">
    <div class="col-xs-offset-6 col-xs-4 form-registo">
        {!! Form::open(array('route' => array('recuperar_password'),'id' => 'resetPassForm')) !!}

        <h5>Recupere a sua password.</h5>

        <div class="row">

            <div class="registo-form col-xs-8">
                <label for="email">Por favor coloque o seu Email.</label>
                <input type="email" name="email" id="email" required="required" aria-required="true">
                <span class="has-error error" style="display:none;"> </span>
            </div>

            <input type="submit" class="brand-botao brand-link formSubmit fleft" value="Enviar" />

        </div>

        {!! Form::close() !!}
    </div>
</div>