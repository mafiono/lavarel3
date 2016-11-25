@extends('portal.profile.layout', [
    'active1' => 'comunicacao',
    'middle' => 'portal.communications.head_communication',
    'active2' => 'mensagens'])

@section('sub-content')

    <div class="row">
        <div class="col-xs-12">
            <div class="title">
                Mensagens: {{\App\Lib\Notifications::getTotalMensagens()}}
            </div>
        </div>
        <div class="col-xs-12">
            <div id="messages-container" class="box-body">
            </div>
        </div>
    </div>

    <div class="row messages">
        {!! Form::open(['url' => 'perfil/mensagens/new', 'method' => 'post', 'enctype'=> "multipart/form-data", 'id' => 'newmessage']) !!}
            <div class="col-xs-12">
                <div class="input-group">
                    <input type="text" id="messagebody" name="message" placeholder="Escreva aqui a sua mensagem..." class="form-control">
                    <span class="input-group-btn">
                        <button type="submit" class="btn btn-success btn-flat">Enviar</button>
                    </span>
                </div>
            </div>
            <div class="col-xs-12">
                <div class="upload">
                    <div id="file_iban" style="cursor:pointer;">
                        <img src="/assets/portal/img/uploadregisto.png"/>
                    </div>
                    <div style="display:none">
                        <input type="File" name="upload" id="fileChooser" onchange="return ValidateFileUpload();">
                    </div>
                    <div id="ficheiro" style="color:grey"></div>
                    <span class="has-error error" style="display:none;"> </span>
                </div>
            </div>
        {!! Form::close() !!}
    </div>
@stop

@section('scripts')
    {!! HTML::script(URL::asset('/assets/portal/js/perfil/messages.js')) !!}
@stop