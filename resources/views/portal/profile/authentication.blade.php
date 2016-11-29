@extends('portal.profile.layout', [
    'active1' => 'perfil',
    'middle' => 'portal.profile.head_profile',
    'active2' => 'autenticacao']
    )

@section('sub-content')

    <div class="row">
        <div class="col-xs-6">
            <div class="title">
                Validação de identidade
            </div>
            <div style="margin-top:5px; margin-bottom:20px;">
                @if ($statusId == 'confirmed')
                    <div class="valido">Válido <img class="icon" src="/assets/portal/img/approved.png"></div>
                @elseif ($statusId == 'waiting_confirmation')
                    <div class="pendente">Pendente <img class="icon" src="/assets/portal/img/pending.png"></div>
                @else
                    <div class="invalido">Inválido <img class="icon" src="/assets/portal/img/declined.png"></div>
                @endif
            </div>
        </div>
        <div class="col-xs-6">
            <div class="title">
                Validação Morada
            </div>
            <div style="margin-top:5px; margin-bottom:20px;">
                @if ($authUser->status->address_status_id == 'confirmed')
                    <div class="valido">Válido <img class="icon" src="/assets/portal/img/approved.png"></div>
                @elseif ($authUser->status->address_status_id == 'waiting_confirmation')
                    <div class="pendente">Pendente <img class="icon" src="/assets/portal/img/pending.png"></div>
                @else
                    <div class="invalido">Inválido <img class="icon" src="/assets/portal/img/declined.png"></div>
                @endif
            </div>
        </div>
    </div>

    @if (isset($docs) && count($docs))
    <div class="row">
        <div class="col-xs-12">
            <div class="title">
                Documentos Enviados
            </div>
        </div>
    </div>
    @foreach($docs as $doc)
        <div class="row docs">
            <div class="col-xs-8">
                <span class="texto">{{$doc->description}}</span>
            </div>
            <div class="col-xs-4">
                <a href="/perfil/download?id={{$doc->id}}" target="_blank"><img src="/assets/portal/img/eye.png"></a>
                <img src="/assets/portal/img/{{$doc->status->id}}.png">
            </div>
        </div>
    @endforeach
    @endif
    <div class="row">
        <div class="col-xs-12">
            <div class="title">
                Enviar Documento
            </div>

            <div class="texto" style="width:100%">
                Para ativar a sua conta deverá submeter uma cópia de um documento emitido pelo país de origem (Carta de condução, Passaporte ou equivalente, com fotografia e data de nascimento)
                e comprovativo de morada, com um tamanho máximo de 5mb.
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-6">
            {!!   Form::open(array('route' => array('perfil/autenticacao/morada'), 'method'=>'POST', 'files'=>true,'id' => 'saveForm')) !!}
            @include('portal.partials.input-file', [
                'field' => 'upload2',
                'name' => 'MORADA',
                'autoSubmit' => true,
            ])
            {!! Form::close() !!}
        </div>
        <div class="col-xs-6">
            {!!   Form::open(array('route' => array('perfil/autenticacao/identity'), 'method'=>'POST', 'files'=>true,'id' => 'saveForm')) !!}
            @include('portal.partials.input-file', [
                'field' => 'upload',
                'name' => 'IDENTIDADE',
                'autoSubmit' => true,
            ])
            {!! Form::close() !!}
        </div>
    </div>
@stop

@section('scripts')

    {!! HTML::script(URL::asset('/assets/portal/js/jquery.validate.js')) !!}
    {!! HTML::script(URL::asset('/assets/portal/js/jquery.validate-additional-methods.js')) !!}

@stop