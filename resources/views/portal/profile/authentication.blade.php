@extends('portal.profile.layout', [
    'active1' => 'perfil',
    'middle' => 'portal.profile.head_profile',
    'active2' => 'autenticacao']
    )

@section('sub-content')



    <div class="left">
        <div class="title">
            Validação de identidade
        </div>
        <div style="margin-top:5px; margin-bottom:20px;">
            @if ($statusId == 'confirmed')
                <font style="vertical-align:center" class="success-color">Válido</font> <img style="margin-left:85px;" src="/assets/portal/img/approved.png">
            @elseif ($statusId == 'waiting_confirmation')
                <font class="info-color">Pendente</font> <img style="margin-left:85px;" src="/assets/portal/img/pending.png">
            @else
                <font class="warning-color">Inválido</font> <img style="margin-left:85px;" src="/assets/portal/img/declined.png">
            @endif
        </div>
    </div>
    <div class="profright">
        <div class="title"> Validação Morada</div>
        <div style="margin-top:5px; margin-bottom:20px;">
            @if ($authUser->status->address_status_id == 'confirmed')
                <font class="success-color">Válido</font> <img style="margin-left:85px;" src="/assets/portal/img/approved.png">
            @elseif ($authUser->status->address_status_id == 'waiting_confirmation')
                <font class="info-color">Pendente</font> <img style="margin-left:85px;" src="/assets/portal/img/pending.png">
            @else
                <font class="warning-color">Inválido</font> <img style="margin-left:85px;" src="/assets/portal/img/declined.png">
            @endif
        </div>
    </div>

    <div class="center">
        @if (isset($docs) && count($docs))

            <div class="title">
                Documentos Enviados
            </div>

            <table class="borderless">
                @foreach($docs as $doc)
                    <tr>
                        <td>{{$doc->description}}</td>
                        <td style="text-align:right;"><a href="/perfil/download?id={{$doc->id}}" target="_blank"><img src="/assets/portal/img/eye.png"></a></td>
                        <td style="text-align:right;"><img src="/assets/portal/img/{{$doc->status->id}}.png"></td>
                    </tr>
                @endforeach
            </table>

        @endif
        <div class="title">
            Enviar Documento
        </div>

        <div class="texto" style="width:100%">
            Para ativar a sua conta deverá submeter uma cópia de um documento emitido pelo país de origem (Carta de condução, Passaporte ou equivalente, com fotografia e data de nascimento)
            e comprovativo de morada, com um tamanho máximo de 5mb.
        </div>
        <div align="center" style="margin-top:20px; margin-bottom:20px;">
            <div class="title2" style="display:inline;cursor:pointer;width:150px;"  id="moradabutton">Morada</div> <div class="title" style="display:inline;cursor:pointer;width:150px;"  id="identidadebutton">Identidade</div>
        </div>
        <div id="morada" style="display:none">

            <div class="upload2"> <div id="file_morada" style="cursor:pointer;"> <img height="250px" width="420px" src="/assets/portal/img/uploadregisto.png" /></div>
                {!!   Form::open(array('route' => array('perfil/autenticacao/morada'),'id' => 'saveForm')) !!}
                <div style="display:none"><input type="File" name="upload2" id="upload2"></div>
                <div id="ficheiro2" style="color:grey"></div>
                <input  style="margin-left:40px;margin-right:0px;" type="submit" value="Enviar">
                {!! Form::close() !!}


            </div>
        </div>


        <div id="identidade">

            <div class="upload"> <div id="file_identidade" style="cursor:pointer;"> <img height="250px" width="420px" src="/assets/portal/img/uploadregisto.png" /></div>
                {!!   Form::open(array('route' => array('perfil/autenticacao'),'id' => 'saveForm')) !!}
                <div style="display:none"><input type="file" id="upload" name="upload"  /></div>
                <div id="ficheiro" style="color:grey"></div>
                <input  style="margin-left:40px;margin-right:0px;" type="submit" value="Enviar">
                {!! Form::close() !!}



            </div>

        </div>

    </div>


@stop

@section('scripts')
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


    {!! HTML::script(URL::asset('/assets/portal/js/jquery.validate.js')) !!}
    {!! HTML::script(URL::asset('/assets/portal/js/jquery.validate-additional-methods.js')) !!}
    {!! HTML::script(URL::asset('/assets/portal/js/plugins/jquery-form/jquery.form.min.js')) !!}
    {!! HTML::script(URL::asset('/assets/portal/js/forms.js')) !!}

@stop