@extends('layouts.register')

<link media="all" type="text/css" rel="stylesheet" href="/assets/portal/css/register.css">
<link href="https://fonts.googleapis.com/css?family=Exo+2" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">

<style>
    i {
        opacity: 0.2 !important;
        filter: alpha(opacity=50) !important; /* For IE8 and earlier */
    }

</style>

@section('content')
    <?php
  $ip = Request::ip();


    ?>
    {!! Form::open(array('route' => array('registar/step1'),'id' => 'saveForm')) !!}

    <div class="register">
        <div class="header">
            Está a 2 passos de começar a apostar!
            <i id="info-close" class="fa fa-times"></i>
        </div>
        <div class="content">
            <div align="center" style="margin-top:10px">
            <div class="breadcrumb flat">
                <a href="#" class="active">1. REGISTO</a>
                <a href="/registar/step2">2. VALIDAÇÃO</a>
                <a href="#">e</a>
            </div>
            </div>


            <div class="row">
                <div class="header">DADOS PESSOAIS</div>
                <div class="column">
                    <div class="row">
                        <div class="label">Título</div>
                        <div class="fieldTop">
                            <input name="gender" id="gender" value="m" type="radio"> Sr. &nbsp;
                            <input name="gender" value="f" type="radio" > Sr.ª
                            <span class="has-error error" style="display:none;"> </span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="label">Primeiro Nome</div>
                        <div class="field"><input name="firstname" id="firstname" class="required" type="text" value="<?php echo !empty($inputs) ? $inputs['firstname'] : ''?>"></div>
                        <span class="has-error error" style="display:none;"> </span>
                    </div>
                    <div class="row">
                        <div class="label">Apelidos</div>
                        <div class="field"><input name="name" id="name" type="text" value="<?php echo !empty($inputs) ? $inputs['name'] : ''?>" > <span class="has-error error"> </span> </div>

                    </div>
                    <div class="row">
                        <div class="label">Identificação Civil</div>
                        <div class="field" ><input type="text" name="document_number" id="document_number" class="required" value="<?php echo !empty($inputs) ? $inputs['document_number'] : ''?>"/></div>
                        <span class="has-error error" style="display:none;"> </span>
                    </div>
                </div>
                <div class="column" style="padding-left:15px;">
                    <div class="row">
                        <div class="label">Data de Nascimento</div>
                        <div class="fieldTop" style="width:171px;">
                            <select name="age_day" style="width:57px;"><?php for ($i=1; $i < 32; $i++): ?>
                                <option value='{{$i}}' <?php echo !empty($inputs) && $inputs['age_day'] == $i ? 'selected'  : ''?>><?php echo sprintf("%02d",$i)?></option>
                                <?php endfor?></select>
                            <select name="age_month" style="width:57px;"> <?php for ($i=1; $i < 13; $i++): ?>
                                <option value='{{$i}}' <?php echo !empty($inputs) && $inputs['age_month'] == $i ? 'selected'  : ''?>><?php echo sprintf("%02d",$i)?></option>
                                <?php endfor?></select>
                            <select name="age_year" style="width:57px;"><?php
                                $year = \Carbon\Carbon::now()->subYears(18)->year;
                                // $year = date("Y") - 18;
                                for ($i=$year; $i > $year - 95; $i--): ?>
                                <option value='{{$i}}' <?php echo !empty($inputs) && $inputs['age_year'] == $i ? 'selected'  : ''?>>{{$i}}</option>
                                <?php endfor?></select>
                            <input name="birth_date" type="hidden">

                        </div>
                    </div>
                    <div class="row">
                        <div class="label">Nacionalidade</div>
                        <div class="field"><select  name="nationality" class="grande"><option selected disabled>-</option>@foreach($natList as $country)<option {{ !empty($inputs) && $inputs['nationality'] == $country ? 'selected'  : ''}}> {{$country}}</option>@endforeach</select></div>

                    </div>
                    <div class="row">
                        <div class="label">Ocupação</div>
                        <div class="field"><select name="sitprofession" class="grande">@foreach($sitProfList as $prof)<option {{ !empty($inputs) && $inputs['sitprofession'] == $prof ? 'selected'  : ''}}>{{$prof}}</option>@endforeach</select></div>

                    </div>
                    <div class="row">
                        <div class="label">Número Fiscal</div>
                        <div class="field"><input type="text" name="tax_number" id="tax_number" class="required" value="<?php echo !empty($inputs) ? $inputs['tax_number'] : ''?>"/></div>
                        <span class="has-error error" style="display:none;"> </span>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="header">CONTACTOS</div>
            </div>
            <div class="row">
                <div class="column">
                    <div class="row">
                        <div class="label">País</div>
                        <div class="field"><select  id="country" name="country" class="grande"><option selected disabled> - </option>@foreach($countryList as $country)<option {{ !empty($inputs) && $inputs['country'] == $country ? 'selected'  : ''}}> {{$country}}</option>@endforeach</select></div>

                    </div>
                    <div class="row">
                        <div class="label">Morada</div>
                        <div class="field"><input type="text" name="address" id="address" class="required" value="<?php echo !empty($inputs) ? $inputs['address'] : ''?>"/></div>
                        <span class="has-error error" style="display:none;"> </span>
                    </div>
                    <div class="row">
                        <div class="label">Email</div>
                        <div class="field"><input type="email" name="email" id="email" class="required" value="<?php echo !empty($inputs) ? $inputs['email'] : ''?>"/></div>
                        <span class="has-error error" style="display:none;"> </span>
                    </div>
                    <div class="row">
                        <div class="label">Repita Email</div>
                        <div class="field"> <input type="email" name="conf_email" id="conf_email" class="required" value="<?php echo !empty($inputs) ? $inputs['email'] : ''?>"/></div>
                        <span class="has-error error" style="display:none;"> </span>
                    </div>
                </div>
                <div class="column" style="padding-left:15px;">
                    <div class="row">
                        <div class="label">Cidade</div>
                        <div class="field"> <input type="text" name="city" id="city" class="required" value="<?php echo !empty($inputs) ? $inputs['city'] : ''?>"/></div>
                        <span class="has-error error" style="display:none;"> </span>
                    </div>
                    <div class="row">
                        <div class="label">Código Postal</div>
                        <div class="field"><input type="text" name="zip_code" class="required" id="zip_code" value="<?php echo !empty($inputs) ? $inputs['zip_code'] : ''?>"/></div>
                        <span class="has-error error" style="display:none;"> </span>
                    </div>
                    <div class="row">
                        <div class="label">Nº de Telefone</div>
                        <div class="field">  <input type="text" name="phone" id="phone" class="required" value="<?php echo !empty($inputs) ? $inputs['phone'] : '+351'?>"/></div>
                        <span class="has-error error" style="display:none;"> </span>
                    </div>
                    <div class="row">
                        <div class="label">Nº de Telemóvel</div>
                        <div class="field"><input type="text" name="phone" id="mobile"  value="<?php echo !empty($inputs) ? $inputs['phone'] : '+351'?>"/></div>
                        <span class="has-error error" style="display:none;"> </span>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="header">CONTA BET PORTUGAL</div>
            </div>
            <div class="row">
                <div class="column">
                    <div class="row">
                        <div class="label">Nome de Utilizador</div>
                        <div class="field"><input type="text" name="username" id="username" class="required" value="<?php echo !empty($inputs) ? $inputs['username'] : ''?>"/></div>
                        <span class="has-error error" style="display:none;"> </span>
                    </div>
                    <div class="row">
                        <div class="label">Palavra Passe</div>
                        <div class="field">  <input type="password" name="password" id="password" class="required"/></div>
                        <span class="has-error error" style="display:none;"> </span>
                    </div>
                    <div class="row">
                        <div class="label">Repita Palavra Passe </div>
                        <div class="field"><input type="password" name="conf_password" id="conf_password" class="required"/></div>
                        <span class="has-error error" style="display:none;"> </span>
                    </div>

                </div>
                <div class="column" style="padding-left:15px;">
                    <div class="row">
                        <div class="label">Moeda</div>
                        <div class="field"><select name="currency" class="grande"><option>EUR - Euro</option></select></div>

                    </div>
                    <div class="row">
                        <div class="label">Código Pin</div>
                        <div class="field"><input size="4" maxlength="4" type="text" name="security_pin" id="security_pin" class="required" value="<?php echo !empty($inputs) ? $inputs['security_pin'] : ''?>"/></div>
                        <span class="has-error error" style="display:none;"> </span>
                    </div>
                    <div class="row">
                        <div class="label">Código Promocional</div>
                        <div class="field"><input type="text" name="promo_code"  value="<?php echo !empty($inputs) ? $inputs['promo_code'] : ''?>"/></div>

                        <span class="has-error error" style="display:none;"> </span>
                    </div>

                </div>
            </div>

        </div>
        <div class="footer">
            <div class="agreement">
                <div class="check" style="margin-bottom:10px;">
                    <input type="checkbox" name="general_conditions" id="general_conditions" class="required"/>
                </div>
                <div class="text">Li e estou de acordo com os <a target="_blank"  href="/info/termos_e_condicoes" >termos e condições</a> e garanto ter no mínimo 18 anos.</div>
            </div>
            <div class="actions" style="margin-bottom:10px;">
                <button type="submit" class="submit formSubmit">VALIDAR</button>

                <button type="button" id="limpar">LIMPAR</button>
            </div>
        </div>


    </div>
    {!! Form::close() !!}
    <script>

    


        $('#info-close').click(function(){

            top.location.replace("/");
        });
        $('#limpar').click(function(){
        $('#saveForm')[0].reset();
        });
    </script>
@stop

@section('scripts')

    {!! HTML::script(URL::asset('/assets/portal/js/jquery.validate.js')); !!}
    {!! HTML::script(URL::asset('/assets/portal/js/jquery.validate-additional-methods.js')); !!}
    {!! HTML::script(URL::asset('/assets/portal/js/plugins/jquery-form/jquery.form.min.js')); !!}
    {!! HTML::script(URL::asset('/assets/portal/js/forms.js')); !!}
    {!! HTML::script(URL::asset('/assets/portal/js/plugins/rx.umd.min.js')) !!}

    {!! HTML::script(URL::asset('/assets/portal/js/registo/step1.js')); !!}
    {!! HTML::script(URL::asset('/assets/portal/js/registo/tooltip.js')); !!}

@stop