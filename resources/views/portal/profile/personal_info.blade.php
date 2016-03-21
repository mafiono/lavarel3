@extends('portal.profile.layout', [
    'active1' => 'perfil',
    'middle' => 'portal.profile.head_profile',
    'active2' => 'info'])

@section('sub-content')

    <div class="col-xs-6 dash-right">
        <div class="title-form-registo brand-title brand-color aleft">
            Informação Pessoal
        </div>

        <div class="registo-form consulta-form">
            <label>Nome Completo</label>
            <input type="text" name="empresa" value="{{ $authUser->profile->name }}" disabled="disabled" />
        </div>

        <div class="registo-form consulta-form">
            <label>Data de Nascimento</label>
            <input type="text" name="data_nascimento" value="{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $authUser->profile->birth_date)->format('Y-m-d') }}" disabled="disabled" />
        </div>


        <div class="registo-form consulta-form">
            <label>Nacionalidade</label>
            <input type="text" name="nacionalidade" value="{{ $authUser->profile->nationality }}" disabled="disabled" />
        </div>

        <div class="registo-form consulta-form">
            <label>Nº Identificação civil ou Passaporte</label>
            <input type="text" name="identificacao" value="{{ $authUser->profile->document_number }}" disabled="disabled" />
        </div>

        <div class="registo-form consulta-form">
            <label>NIF</label>
            <input type="text" name="nif"  value="{{ $authUser->profile->tax_number }}" disabled="disabled" />
        </div>

        <div class="registo-form consulta-form">
            <label>Email</label>
            <input type="text" name="email"  value="{{ $authUser->profile->email }}" disabled="disabled" />
        </div>
    </div>

    {!! Form::open(array('route' => array('perfil'),'id' => 'saveForm')) !!}

        <div class="col-xs-6">
            <div class="title-form-registo brand-title brand-color aleft">
                Alterar Detalhes
            </div>

            <div class="registo-form consulta-form">
                <label>Profissão</label>
                <input type="text" name="profession" id="profession" value="{{ $authUser->profile->profession }}" class="required"/>
                <span class="has-error error" style="display:none;"> </span>
            </div>
            <div class="registo-form consulta-form">
                <label>Telefone</label>
                <input type="text" name="phone" class="required" id="phone" value="{{ $authUser->profile->phone }}"/>
                <span class="has-error error" style="display:none;"> </span>
            </div>
            <div class="registo-form consulta-form">
                <label>Pais</label>
                {!! Form::select('country', $countryList, !empty($inputs) ? $inputs['country'] : 'PT') !!}
                <span class="has-error error" style="display:none;"> </span>
            </div>
            <div class="registo-form consulta-form">
                 <label>Morada</label>
                 <input type="text" name="address" id="address" class="required" value="{{ $authUser->profile->address }}"/>
                 <span class="has-error error" style="display:none;"> </span>
            </div>
            <div class="registo-form consulta-form">
                <label>Cidade</label>
                <input type="text" name="city" id="city" class="required" value="{{ $authUser->profile->city }}"/>
                <span class="has-error error" style="display:none;"> </span>
            </div>
            <div class="registo-form consulta-form">
                <label>Código Postal</label>
                <input type="text" name="zip_code" class="required" id="zip_code" value="{{ $authUser->profile->zip_code }}"/>
                <span class="has-error error" style="display:none;"> </span>
            </div>
            <div class="registo-form consulta-form" id="file_morada" style="display: none">
                <label>Comprovativo Morada</label>
                <input type="file" id="upload" name="upload"
                       class="col-xs-6 brand-botao brand-link upload-input ignore" />
                <span class="has-error error" style="display:none;"> </span>
            </div>

            @include('portal.messages')
        </div>
        <div class="clear"></div>

        <div class="form-rodape col-xs-12">
            <input type="submit" class="col-xs-2 brand-botao fright brand-link formSubmit" value="Alterar Info" />
            <div class="clear"></div>
        </div>

    {!! Form::close() !!}
@stop

@section('scripts')

    {!! HTML::script(URL::asset('/assets/portal/js/jquery.validate.js')); !!}    
    {!! HTML::script(URL::asset('/assets/portal/js/jquery.validate-additional-methods.js')); !!}
    {!! HTML::script(URL::asset('/assets/portal/js/plugins/jquery-form/jquery.form.min.js')); !!}
    {!! HTML::script(URL::asset('/assets/portal/js/forms.js')); !!}
    {!! HTML::script(URL::asset('/assets/portal/js/plugins/rx.umd.min.js')); !!}
    {!! HTML::script(URL::asset('/assets/portal/js/perfil/personal_info.js')); !!}

@stop