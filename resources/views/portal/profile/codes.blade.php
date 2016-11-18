@extends('portal.profile.layout', [
    'active1' => 'perfil',
    'middle' => 'portal.profile.head_profile',
    'active2' => 'codes',]
)

@section('sub-content')

    <div class="row">
        <div class="col-xs-6">
            {!! Form::open(array('route' => array('perfil/codigos/password'),'id' => 'saveFormPass')) !!}
            <div class="title">
                Alterar Palavra Passe
            </div>

            @include('portal.partials.input-password', [
                'field' => 'old_password',
                'name' => 'Palavra Passe Atual',
                'required' => true,
            ])

            @include('portal.partials.input-password', [
                'field' => 'password',
                'name' => 'Nova Palavra Passe',
                'required' => true,
            ])

            @include('portal.partials.input-password', [
                'field' => 'conf_password',
                'name' => 'Confirme Nova Palavra Passe',
                'required' => true,
            ])

            <input type="submit" value="Guardar">
            {!! Form::close() !!}
        </div>
        <div class="col-xs-6">
            {!! Form::open(array('route' => array('perfil/codigos/codigo-pin'),'id' => 'saveFormPin')) !!}
            <div class="title">Alteração de Código PIN</div>

            @include('portal.partials.input-text', [
                'field' => 'old_security_pin',
                'name' => 'Código PIN Atual',
                'required' => true,
            ])

            @include('portal.partials.input-text', [
                'field' => 'security_pin',
                'name' => 'Novo Código PIN',
                'required' => true,
            ])

            @include('portal.partials.input-text', [
                'field' => 'conf_security_pin',
                'name' => 'Confirme Novo Código PIN',
                'required' => true,
            ])

            <input type="submit" value="Guardar">
           {!! Form::close() !!}
        </div>
    </div>

    @include('portal.messages')

@stop

@section('scripts')

    {!! HTML::script(URL::asset('/assets/portal/js/jquery.validate.js')); !!}    
    {!! HTML::script(URL::asset('/assets/portal/js/jquery.validate-additional-methods.js')); !!}
    {!! HTML::script(URL::asset('/assets/portal/js/plugins/jquery-form/jquery.form.min.js')); !!}
    {!! HTML::script(URL::asset('/assets/portal/js/forms.js')); !!}

    <script type="text/javascript">
        function suss(label, input) {
            input = $(input);
            input.siblings('.success-color').remove();
            input.after('<i class="fa fa-check-circle success-color"></i>');
            input.siblings('.warning-color').remove();
        }
        function err(error, input) {
            input = $(input);
            input.siblings('.warning-color').remove();
            input.siblings('span').find('.warning-color').remove();
            input.after('<span><font class="warning-color">'+error.text()+'</font></span>');
            input.after('<i class="fa fa-times-circle warning-color"></i>');
            input.siblings('.success-color').remove();
        }
        $("#saveFormPass").validate({
            success: suss,
            errorPlacement: err,
            rules: {
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
            },
            messages: {
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
            }
        });

        $("#saveFormPin").validate({
            success: suss,
            errorPlacement: err,
            rules: {
                old_security_pin: {
                    required: true
                },
                security_pin: {
                    required: true,
                    minlength: 4,
                    maxlength: 4
                },
                conf_security_pin: {
                    required: true,
                    equalTo: "#security_pin"
                }
            },
            messages: {
                old_security_pin: {
                    required: "Preencha o seu código de segurança antigo"
                },
                security_pin: {
                    required: "Preencha o seu código de segurança",
                    minlength: "O código de segurança tem de ter 4 numeros",
                    maxlength: "O código de segurança tem de ter 4 numeros"
                },
                conf_security_pin: {
                    required: "Confirme o seu código de segurança",
                    equalTo: "Este campo tem de ser igual ao seu código de segurança"
                }
            }
        });
    </script>

@stop