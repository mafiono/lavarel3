@extends('portal.profile.layout', [
    'active1' => 'comunicacao',
    'active2' => 'mensagens'])

@section('sub-content')

    <div class="row">
        <div class="col-xs-12">
            <div class="title">
                Mensagens: <span class="messages-total"></span>
            </div>
        </div>
        <div class="col-xs-12">
            <div id="messages-container" class="box-body">
                @foreach($messages as $item)
                    <div class="row msg {{$item->staff?'staff':'user'}}">
                        <div class="col-xs-12 msg-title">{{$item->created_at}}<span></span></div>
                        <div class="col-xs-12 msg-body">{{$item->text}}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="row messages">
        {!! Form::open(['url' => 'perfil/mensagens/new', 'method' => 'post', 'id' => 'newmessage']) !!}
            <div class="col-xs-12">
                <div class="input-group">
                    <input type="text" id="messagebody" name="message" placeholder="Escreva aqui a sua mensagem..." class="form-control">
                    <span class="input-group-btn">
                        <button type="submit" class="btn btn-success btn-flat">Enviar</button>
                    </span>
                </div>
            </div>
        {!! Form::close() !!}
        {!! Form::open(['url' => 'perfil/mensagens/upload', 'method' => 'post', 'enctype'=> "multipart/form-data", 'id' => 'newupload']) !!}
            <div class="col-xs-12">
                @include('portal.partials.input-file', [
                    'field' => 'image',
                    'name' => 'seleccionar arquivo',
                    'autoSubmit' => true,
                ])
            </div>
        {!! Form::close() !!}
    </div>
@stop