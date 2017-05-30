@extends('portal.profile.layout', [
    'active1' => 'perfil',
    'middle' => 'portal.profile.head_profile',
    'active2' => 'info'])

@section('sub-content')
    {!! Form::open(array('route' => 'perfil', 'method'=>'POST', 'files'=>true, 'id' => 'saveForm')) !!}

    <div class="row">
        <div class="col-xs-5 col-sm-12" style="overflow-x:visible">
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

        </div>
        <div class="col-xs-7 col-sm-12">
            <div class="title">
                Alterar Detalhes
            </div>

            @include('portal.partials.input-select', [
                'field' => 'sitprofession',
                'name' => 'Ocupação',
                'options' => $sitProfList,
                'value' => $authUser->profile->professional_situation,
                'required' => true,
            ])

            @include('portal.partials.input-text', [
                'field' => 'address',
                'name' => 'Morada',
                'value' => $authUser->profile->address,
                'required' => true,
            ])

            <div class="row slim-row">
                <div class="col-xs-5">
                    @include('portal.partials.input-text', [
                        'field' => 'zip_code',
                        'name' => 'Cód Postal',
                        'value' => $authUser->profile->zip_code,
                        'required' => true,
                    ])
                </div>
                <div class="col-xs-7">
                    @include('portal.partials.input-text', [
                        'field' => 'city',
                        'name' => 'Cidade',
                        'value' => $authUser->profile->city,
                        'required' => true,
                    ])
                </div>
            </div>

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

        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            @include('portal.partials.input-text-disabled', [
                     'field' => 'email',
                     'name' => 'Email',
                     'value' => $authUser->profile->email
                 ])
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <div id="file_morada" style="display: none">
                @include('portal.partials.input-file', [
                    'field' => 'upload',
                    'name' => 'Enviar<br>MORADA',
                    'autoSubmit' => false,
                ])
            </div>
            <div class="profile-button-right">
                <input type="submit" value="Guardar"/>
            </div>
        </div>
    </div>
    {!! Form::close() !!}
@stop