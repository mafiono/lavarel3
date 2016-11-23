@extends('portal.profile.layout', [
    'active1' => 'comunicacao',
    'middle' => 'portal.communications.head_communication',
    'active2' => 'definicoes',
])

@section('sub-content')
    {!!   Form::open(array('route' => array('comunicacao/definicoes'), 'id' => 'saveForm')) !!}

    <div class="row">
        <div class="col-xs-12">
            <div class="title">
                Definições de Comunicação
            </div>
            <div class="texto" style="margin-top:15px;">
                Os pedidos de levantamento serão efetuados na conta acima indicada. A alteração desta conta
                inviabiliza o processamento de levantamentos por um
                período de 48 horas, necessário para rotinas de confirmação de titular.
            </div>
        </div>
    </div>
    <div class="row settings">
        @include('portal.communications.input-radio', [
            'fieldName' => 'Email',
            'field' => 'email',
            'value' => $settings['email'],
        ])
        @include('portal.communications.input-radio', [
            'fieldName' => 'Telefone',
            'field' => 'phone',
            'value' => $settings['phone'],
        ])
        @include('portal.communications.input-radio', [
            'fieldName' => 'Sms',
            'field' => 'sms',
            'value' => $settings['sms'],
        ])
        @include('portal.communications.input-radio', [
            'fieldName' => 'Correio',
            'field' => 'mail',
            'value' => $settings['mail'],
        ])
        @include('portal.communications.input-radio', [
            'fieldName' => 'Newsletter',
            'field' => 'newsletter',
            'value' => $settings['newsletter'],
        ])
        @include('portal.communications.input-radio', [
            'fieldName' => 'Chat',
            'field' => 'chat',
            'value' => $settings['chat'],
        ])
    </div>

    {!! Form::close() !!}

@stop

@section('scripts')
    {!! HTML::script(URL::asset('/assets/portal/js/jquery.validate.js')); !!}
    {!! HTML::script(URL::asset('/assets/portal/js/jquery.validate-additional-methods.js')); !!}
    {!! HTML::script(URL::asset('/assets/portal/js/plugins/jquery-form/jquery.form.min.js')); !!}
    {!! HTML::script(URL::asset('/assets/portal/js/forms.js')); !!}

    <script>
        var form = $('#saveForm');
        $('.grupo .settings-switch').change(function () {
            form.submit();
        });
    </script>
@stop