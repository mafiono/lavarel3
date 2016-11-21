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

    {!! HTML::script(URL::asset('/assets/portal/js/perfil/codigos_acesso.js')); !!}


@stop