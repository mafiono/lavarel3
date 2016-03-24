@extends('portal.profile.layout', [
    'active1' => 'perfil',
    'middle' => 'portal.profile.head_profile',
    'active2' => 'pin',
    'form' => array('route' => array('perfil/codigo-pin'),'id' => 'saveForm'),
    'btn' => 'Guardar Pin'])

@section('sub-content')

    <div class="col-xs-6">
        <div class="title-form-registo brand-title brand-color aleft">
            Alteração de Código PIN
        </div>

        <div class="registo-form">
            <label>Antigo Pin</label>
            <input type="password" name="old_security_pin" id="old_security_pin" class="required"/>
            <span class="has-error error" style="display:none;"> </span>
        </div>

        <div class="registo-form">
            <label>Novo Pin</label>
            <input type="password" name="security_pin" id="security_pin" class="required"/>
            <span class="has-error error" style="display:none;"> </span>
        </div>

        <div class="registo-form">
            <label>Confirmação Pin</label>
            <input type="password" name="conf_security_pin" id="conf_security_pin" class="required"/>
            <span class="has-error error" style="display:none;"> </span>
        </div>

        @include('portal.messages')
    </div>
    <div class="clear"></div>

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
                minlength: 5
            },
            conf_security_pin: {
                required: true,
                minlength: 5,
                equalTo: "#security_pin"
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
            }            
        };
            
    </script>

@stop