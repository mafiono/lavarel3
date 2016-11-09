@extends('portal.profile.layout', [
    'active1' => 'perfil',
    'middle' => 'portal.profile.head_profile',
    'active2' => 'info',
    'form' => array('route' => array('perfil'),'id' => 'saveForm'),
    'btn' => 'Guardar'])

@section('sub-content')

    <div class="left">

        <div class="title">
            Dados Pessoais
        </div>

        <div class="input-title">Nome Completo</div>
            <input class="disabled" type="text" name="empresa" value="{{ $authUser->profile->name }}" disabled="disabled" />

        <div class="input-title">Data de Nascimento</div>
            <input class="disabled" type="text" name="data_nascimento" value="{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $authUser->profile->birth_date)->format('Y-m-d') }}" disabled="disabled" />

        <div class="input-title">Nacionalidade</div>
            <input class="disabled" type="text" name="nacionalidade" value="{{ $authUser->profile->nationality }}" disabled="disabled" />

        <div class="input-title">Nº Identificação Civil</div>
            <input class="disabled" type="text" name="identificacao" value="{{ $authUser->profile->document_number }}" disabled="disabled" />

        <div class="input-title">NIF</div>
            <input class="disabled" type="text" name="nif"  value="{{ $authUser->profile->tax_number }}" disabled="disabled" />

        <div class="input-title">Email</div>
            <input class="disabled" type="text" name="email"  value="{{ $authUser->profile->email }}" disabled="disabled" />

    </div>
    <div class="profright">

            <div class="title">
                Alterar Detalhes
            </div>

        <div class="input-title">Ocupação</div>
        <input type="text" name="profession" id="profession" value="{{ $authUser->profile->profession }}" class="required"/>
        <span class="has-error error" style="display:none;"> </span>

        <div class="input-title">Morada</div>
        <input type="text" name="address" id="address" class="required" value="{{ $authUser->profile->address }}"/>
        <span class="has-error error" style="display:none;"> </span>

        <div class="input-title">Cód Postal</div>
        <input type="text" name="zip_code" class="required" id="zip_code" value="{{ $authUser->profile->zip_code }}"/>
        <span class="has-error error" style="display:none;"> </span>

        <div class="input-title">Cidade</div>
        <input type="text" name="city" id="city" class="required" value="{{ $authUser->profile->city }}"/>
        <span class="has-error error" style="display:none;"> </span>

        <div class="input-title">País</div>
        {!! Form::select('country', $countryList, !empty($inputs) ? $inputs['country'] : 'PT') !!}
        <span class="has-error error" style="display:none;"> </span>

        <div class="input-title">Telemóvel</div>
        <input type="text" name="phone" class="required" id="phone" value="{{ $authUser->profile->phone }}"/>
        <span class="has-error error" style="display:none;"> </span>

        <div class="upload"> <div id="file_morada" style="cursor:pointer;display:none"><div class="input-title">Comprovativo Morada</div> <img height="100px" width="200px" src="/assets/portal/img/uploadregisto.png" /></div>

            <div style="display:none"><input type="File" name="upload2" id="upload">
            </div>
            <div class="profile-info" id="ficheiro"></div>

        </div>

            @include('portal.messages')
        </div>

<script>
    $("#file_morada").click(function () {
        $("#upload").trigger('click');
    });
    $('#upload').change(function(){
        var fileName = $(this).val();
        $('#ficheiro').text(fileName);
    });
</script>
@stop

@section('scripts')

    {!! HTML::script(URL::asset('/assets/portal/js/jquery.validate.js')); !!}    
    {!! HTML::script(URL::asset('/assets/portal/js/jquery.validate-additional-methods.js')); !!}
    {!! HTML::script(URL::asset('/assets/portal/js/plugins/jquery-form/jquery.form.min.js')); !!}
    {!! HTML::script(URL::asset('/assets/portal/js/forms.js')); !!}
    {!! HTML::script(URL::asset('/assets/portal/js/plugins/rx.umd.min.js')); !!}
    {!! HTML::script(URL::asset('/assets/portal/js/perfil/personal_info.js')); !!}

@stop