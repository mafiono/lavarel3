@extends('portal.profile.layout', [
    'active1' => 'perfil',
    'middle' => 'portal.profile.head_profile',
    'active2' => 'info',
    'form' => array('route' => array('perfil'),'id' => 'saveForm'),
    'btn' => 'Guardar'])

@section('sub-content')

    <div class="row">
        <div class="col-xs-5">
            <div class="title">
                Dados Pessoais
            </div>

            @include('portal.partials.input-text-disabled', [
                'field' => 'nome',
                'name' => 'Nome Completo',
                'value' => $authUser->profile->name,
            ])
            @include('portal.partials.input-text-disabled', [
                'field' => 'data_nascimento',
                'name' => 'Data de Nascimento',
                'value' => \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $authUser->profile->birth_date)->format('Y-m-d')
            ])
            @include('portal.partials.input-text-disabled', [
                'field' => 'nacionalidade',
                'name' => 'Nacionalidade',
                'value' => $authUser->profile->nationality
            ])
            @include('portal.partials.input-text-disabled', [
                'field' => 'identificacao',
                'name' => 'Nº Identificação Civil',
                'value' => $authUser->profile->document_number
            ])
            @include('portal.partials.input-text-disabled', [
                'field' => 'nif',
                'name' => 'NIF',
                'value' => $authUser->profile->tax_number
            ])
            @include('portal.partials.input-text-disabled', [
                'field' => 'email',
                'name' => 'Email',
                'value' => $authUser->profile->email
            ])

        </div>
        <div class="col-xs-7">
            <div class="title">
                Alterar Detalhes
            </div>

            @include('portal.partials.input-text', [
                'field' => 'profession',
                'name' => 'Ocupação',
                'value' => $authUser->profile->profession,
                'required' => true,
            ])

            @include('portal.partials.input-text', [
                'field' => 'address',
                'name' => 'Morada',
                'value' => $authUser->profile->address,
                'required' => true,
            ])

            @include('portal.partials.input-text', [
                'field' => 'zip_code',
                'name' => 'Cód Postal',
                'value' => $authUser->profile->zip_code,
                'required' => true,
            ])

            @include('portal.partials.input-text', [
                'field' => 'city',
                'name' => 'Cidade',
                'value' => $authUser->profile->city,
                'required' => true,
            ])

            @include('portal.partials.input-select', [
                'field' => 'country',
                'name' => 'País',
                'options' => $countryList,
                'value' => !empty($inputs) ? $inputs['country'] : 'PT',
                'required' => true,
            ])

            @include('portal.partials.input-text', [
                'field' => 'phone',
                'name' => 'Telemóvel',
                'value' => $authUser->profile->phone,
                'required' => true,
            ])

            <div class="upload">
                <div id="file_morada" style="cursor:pointer;display:none">
                    <div class="input-title">Comprovativo Morada</div>
                    <img src="/assets/portal/img/morada.png"/>
                </div>
                <div style="display:none"><input type="File" name="upload" id="upload">
                </div>
                <div class="profile-info" id="ficheiro"></div>

            </div>
        </div>
    </div>
    @include('portal.messages')

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