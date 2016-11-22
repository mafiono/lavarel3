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
    <div class="left">
        <div class="upload2"> <div id="file_morada" style="cursor:pointer;"> <img style="margin-top:30px;" height="200px" width="200px" src="/assets/portal/img/morada.png" /></div>
            {!!   Form::open(array('route' => array('perfil/autenticacao/morada'), 'method'=>'POST', 'files'=>true,'id' => 'saveForm')) !!}
            <div style="display:none"><input onchange="this.form.submit()" type="File" name="upload2" id="upload2"></div>
            <div id="ficheiro2" style="color:grey"></div>
            {!! Form::close() !!}
        </div>
    </div>
    <div class="profright">
        <div class="upload"> <div id="file_identidade" style="cursor:pointer;"> <img style="margin-top:30px;"  height="200px" width="200px" src="/assets/portal/img/identidade.png" /></div>
            {!!   Form::open(array('route' => array('perfil/autenticacao/identity'), 'method'=>'POST', 'files'=>true,'id' => 'saveForm')) !!}
            <div style="display:none"><input onchange="this.form.submit()" type="file" id="upload" name="upload"  /></div>
            <div id="ficheiro" style="color:grey"></div>
            {!! Form::close() !!}
        </div>
    </div>
@stop

@section('scripts')

    {!! HTML::script(URL::asset('/assets/portal/js/jquery.validate.js')) !!}
    {!! HTML::script(URL::asset('/assets/portal/js/jquery.validate-additional-methods.js')) !!}
    {!! HTML::script(URL::asset('/assets/portal/js/plugins/jquery-form/jquery.form.min.js')) !!}
    {!! HTML::script(URL::asset('/assets/portal/js/forms.js')) !!}

    <script>

        $("#file_identidade").click(function () {
            $("#upload").trigger('click');
        });
        $('#upload').change(function(){
            var fileName = $(this).val();
            $('#ficheiro').text(fileName);
        });

        $("#file_morada").click(function () {
            $("#upload2").trigger('click');
        });
        $('#upload2').change(function(){
            var fileName = $(this).val();
            $('#ficheiro2').text(fileName);
        });
        $( "#moradabutton" ).click(function() {
            $( "#moradabutton" ).removeClass('title2');
            $( "#moradabutton" ).addClass('title');
            $( "#identidadebutton" ).removeClass('title');
            $( "#identidadebutton" ).addClass('title2');

            $( "#identidade" ).hide();
            $( "#morada" ).show();


        });

        $( "#identidadebutton" ).click(function() {
            $( "#moradabutton" ).removeClass('title');
            $( "#moradabutton" ).addClass('title2');
            $( "#identidadebutton" ).removeClass('title2');
            $( "#identidadebutton" ).addClass('title');

            $( "#identidade" ).show();
            $( "#morada" ).hide();
        });
    </script>

@stop