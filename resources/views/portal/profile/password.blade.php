@extends('portal.profile.layout', [
    'active1' => 'perfil',
    'middle' => 'portal.profile.head_profile',
    'active2' => 'password'])

@section('sub-content')

            {!! Form::open(array('route' => array('perfil/password'),'id' => 'saveForm')) !!}
                <div class="col-xs-6">
                    <div class="title-form-registo brand-title brand-color aleft">
                        Alteração de Password
                    </div>

                    <div class="registo-form">
                        <label>Antiga Password</label>
                        <input type="password" name="old_password" id="old_password" class="required"/>
                        <span class="has-error error" style="display:none;"> </span>
                    </div>

                    <div class="registo-form">
                        <label>Password</label>
                        <input type="password" name="password" id="password" class="required"/>
                        <span class="has-error error" style="display:none;"> </span>
                    </div>

                    <div class="registo-form">
                        <label>Confirmação Password</label>
                        <input type="password" name="conf_password" id="conf_password" class="required"/>
                        <span class="has-error error" style="display:none;"> </span>
                    </div>

                    @include('portal.messages')
                </div>
                <div class="clear"></div>

                <div class="form-rodape col-xs-12">
                    <input type="submit" class="col-xs-2 brand-botao fright brand-link formSubmit" value="Guardar Pass" />
                    <div class="clear"></div>
                </div>
            {!! Form::close() !!}


        @include('portal.profile.bottom')
                        
@stop

@section('scripts')

    {!! HTML::script(URL::asset('/assets/portal/js/jquery.validate.js')); !!}    
    {!! HTML::script(URL::asset('/assets/portal/js/jquery.validate-additional-methods.js')); !!}
    {!! HTML::script(URL::asset('/assets/portal/js/plugins/jquery-form/jquery.form.min.js')); !!}
    {!! HTML::script(URL::asset('/assets/portal/js/forms.js')); !!}

    <script type="text/javascript">

        var rules = {
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