@extends('portal.profile.layout', [
    'active1' => 'comunicacao',
    'active2' => 'definicoes',
])

@section('sub-content')
    {!!   Form::open(array('route' => array('comunicacao/definicoes'), 'id' => 'saveForm')) !!}
    <div class="row">
        <div class="col-xs-12">
            <div class="title">
                Definições de Comunicação
            </div>
            <div class="texto" align="justify" style="margin-top:15px;">
                Defina aqui as suas preferências na forma como gostaria de ser contactado pelo Casino Portugal. É muito importante que mantenha esta área atualizada para poder receber ofertas e recomendações personalizadas da forma que lhe for mais conveniente.
            </div>
        </div>
    </div>
    <div class="row settings">
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
            'fieldName' => 'WhatsApp',
            'field' => 'whatsapp',
            'value' => $settings['whatsapp'],
        ])
    </div>
    {!! Form::close() !!}
@stop