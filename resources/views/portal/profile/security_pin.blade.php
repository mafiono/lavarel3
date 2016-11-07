@extends('portal.profile.layout', [
    'active1' => 'perfil',
    'middle' => 'portal.profile.head_profile',
    'active2' => 'pin',]
)

@section('sub-content')

    <div class="left">
        {!! Form::open(array('route' => array('perfil/password'),'id' => 'saveForm')) !!}
        <div class="title">
            Alterar Palavra Passe
        </div>

        <div class="input-title">Palavra Passe Atual</div>
        <input type="password" name="old_password" id="old_password" class="required"/>
        <span class="has-error error" style="display:none;"> </span>


        <div class="input-title">Nova Palavra Passe</div>
        <input type="password" name="password" id="password" class="required"/>
        <span class="has-error error" style="display:none;"> </span>



        <div class="input-title">Confirme Nova Palavra Passe</div>
        <input type="password" name="conf_password" id="conf_password" class="required"/>
        <span class="has-error error" style="display:none;"> </span>

        <input type="submit" value="Guardar">
        @include('portal.messages')
        {!! Form::close() !!}
    </div>
    {!! Form::open(array('route' => array('perfil/codigo-pin'),'id' => 'saveForm')) !!}

    <div class="profright">
        <div class="title">Alteração de Código PIN</div>


        <div class="input-title">Código PIN Atual</div>
        <input size="4" maxlength="4" type="text" name="old_security_pin" id="old_security_pin" class="required"/>
        <span class="has-error error" style="display:none;"> </span>



        <div class="input-title">Novo Código PIN </div>
        <input size="4" maxlength="4" type="text" name="security_pin" id="security_pin" class="required"/>
        <span class="has-error error" style="display:none;"> </span>



        <div class="input-title">Confirme Novo Código PIN</div>
        <input size="4" maxlength="4" type="text" name="conf_security_pin" id="conf_security_pin" class="required"/>
        <span class="has-error error" style="display:none;"> </span>

        <input type="submit" value="Guardar">
        @include('portal.messages')
       {!! Form::close() !!}
    </div>




@stop

@section('scripts')

    {!! HTML::script(URL::asset('/assets/portal/js/jquery.validate.js')); !!}    
    {!! HTML::script(URL::asset('/assets/portal/js/jquery.validate-additional-methods.js')); !!}
    {!! HTML::script(URL::asset('/assets/portal/js/plugins/jquery-form/jquery.form.min.js')); !!}
    {!! HTML::script(URL::asset('/assets/portal/js/forms.js')); !!}

    <script type="text/javascript">

        var rules = {
            old_security_pin: {
                required: true
            },            
            security_pin: {
                required: true,
                minlength: 4
            },
            conf_security_pin: {
                required: true,
                minlength: 4,
                equalTo: "#security_pin"
            }     ,
            old_password: {
                required: true
            },
            password: {
                required: true,
                minlength: 6
            },
            conf_password: {
                required: true,
                minlength: 6,
                equalTo: "#password"
            }
        };

        var messages = {              
            old_security_pin: {
                required: "Preencha o seu código de segurança antigo",
            },            
            security_pin: {
                required: "Preencha o seu código de segurança",
                minlength: "O código de segurança tem de ter pelo menos 5 caracteres"
            },
            conf_security_pin: {
                required: "Confirme o seu código de segurança",
                minlength: "O código de segurança tem de ter pelo menos 5 caracteres",
                equalTo: "Este campo tem de ser igual ao seu código de segurança"
            }   ,
            old_password: {
                required: "Preencha a sua antiga password"
            },
            password: {
                required: "Preencha a sua password",
                minlength: "A password tem de ter pelo menos 6 caracteres"
            },
            conf_password: {
                required: "Confirme a sua password",
                minlength: "A password tem de ter pelo menos 6 caracteres",
                equalTo: "Este campo tem de ser igual à sua password"
            }
        };
            
    </script>

@stop