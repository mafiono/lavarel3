@extends('portal.sign_up.register')

@section('content')
    {!! Form::open(array('route' => array('registar/step1'),'id' => 'saveForm')) !!}
    <div class="register">
        <div class="title">
            Está a 3 passos de começar a apostar!
            <i id="register-close" class="cp-cross"></i>
        </div>
        <div class="content">
            <div align="center" style="margin-top:10px">
                <ul class="breadcrumb flat">
                    <li class="active">1. REGISTAR</li>
                    <li>2. VALIDAR</li>
                    <li>3. DEPOSITAR</li>
                    <li>e</li>
                </ul>
            </div>
            <div class="header">DADOS PESSOAIS</div>
            <div class="row">
                <div class="column">
                    <div class="row">
                        <label>Título <b>*</b></label>
                        <div class="field top">
                            <input name="gender" id="gender"    value="m" type="radio" {{Helper::ifTrue($inputs, 'gender', 'm', 'checked')}}> Sr.
                            <input name="gender"                value="f" type="radio" {{Helper::ifTrue($inputs, 'gender', 'f', 'checked')}}> Sr.ª
                            <span class="has-error error" style="display:none;"> </span>
                        </div>
                    </div>
                    <div class="row">
                        <label>Primeiro Nome <b>*</b></label>
                        <div class="field"><input name="firstname" id="firstname" class="required" type="text" value="{{Helper::getKey($inputs, 'firstname')}}"></div>
                        <span class="has-error error" style="display:none;"> </span>
                    </div>
                    <div class="row">
                        <label>Apelidos <b>*</b></label>
                        <div class="field"><input name="name" id="name" type="text" value="{{Helper::getKey($inputs, 'name')}}" > <span class="has-error error"> </span> </div>
                    </div>
                    <div class="row">
                        <label>Identificação Civil <b>*</b></label>
                        <div class="field" ><input type="text" name="document_number" id="document_number" class="required" value="{{Helper::getKey($inputs, 'document_number')}}"/></div>
                        <span class="has-error error" style="display:none;"> </span>
                    </div>
                </div>
                <div class="column">
                    <div class="row">
                        <label>Data de Nascimento <b>*</b></label>
                        <div class="field birth-date">
                            <input name="birth_date" id="birth_date" type="hidden">
                            <select name="age_day" id="age_day">
                                <option value="">-</option>
                                <?php for ($i=1; $i < 32; $i++): ?>
                                <option value='{{$i}}' {{Helper::ifTrue($inputs, 'age_day', $i, 'selected')}}><?php echo sprintf("%02d",$i)?></option>
                                <?php endfor?></select>
                            <select name="age_month" id="age_month">
                                <option value="">-</option>
                                <?php for ($i=1; $i < 13; $i++): ?>
                                <option value='{{$i}}' {{Helper::ifTrue($inputs, 'age_month', $i, 'selected')}}><?php echo sprintf("%02d",$i)?></option>
                                <?php endfor?></select>
                            <select name="age_year" id="age_year">
                                <option value="">-</option>
                                <?php
                                $year = \Carbon\Carbon::now()->subYears(18)->year;
                                // $year = date("Y") - 18;
                                for ($i=$year; $i > $year - 95; $i--): ?>
                                <option value='{{$i}}' {{Helper::ifTrue($inputs, 'age_year', $i, 'selected')}}>{{$i}}</option>
                                <?php endfor?></select>
                            <span class="has-error error" style="display:none;"> </span>
                        </div>
                    </div>
                    <div class="row">
                        <label>Nacionalidade <b>*</b></label>
                        <div class="field">
                            <select id="nationality" name="nationality">
                                <option selected disabled>-</option>
                                <option value="PT" {{Helper::ifTrue($inputs, 'nationality', 'PT', 'selected')}}>{{$natList['PT']}}</option>
                                @foreach($natList as $key => $country)
                                    <option value="{{$key}}" {{Helper::ifTrue($inputs, 'nationality', $key, 'selected')}}> {{$country}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <label>Ocupação <b>*</b></label>
                        <div class="field">
                            <select id="sitprofession" name="sitprofession">
                                @foreach($sitProfList as $key => $prof)
                                    <option value="{{$key}}" {{Helper::ifTrue($inputs, 'sitprofession', $key, 'selected')}}>{{$prof}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <label>Número Fiscal <b>*</b></label>
                        <div class="field"><input type="text" name="tax_number" id="tax_number" class="required" value="{{Helper::getKey($inputs, 'tax_number')}}"/></div>
                        <span class="has-error error" style="display:none;"> </span>
                    </div>
                </div>
            </div>
            <div class="header">CONTACTOS</div>
            <div class="row">
                <div class="column">
                    <div class="row">
                        <label>País <b>*</b></label>
                        <div class="field">
                            <select id="country" name="country">
                                <option selected disabled>-</option>
                                <option value="PT" {{Helper::ifTrue($inputs, 'country', 'PT', 'selected')}}>{{$countryList['PT']}}</option>
                                @foreach($countryList as $key => $country)
                                    <option value="{{$key}}" {{Helper::ifTrue($inputs, 'country', $key, 'selected')}}> {{$country}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <label>Morada <b>*</b></label>
                        <div class="field"><input type="text" name="address" id="address" class="required" value="{{Helper::getKey($inputs, 'address')}}"/></div>
                        <span class="has-error error" style="display:none;"> </span>
                    </div>
                    <div class="row">
                        <label>Email <b>*</b></label>
                        <div class="field"><input type="email" name="email" id="email" class="required" value="{{Helper::getKey($inputs, 'email')}}"/></div>
                        <span class="has-error error" style="display:none;"> </span>
                    </div>
                    <div class="row">
                        <label>Repita Email <b>*</b></label>
                        <div class="field"> <input type="email" name="conf_email" id="conf_email" class="required" value="{{Helper::getKey($inputs, 'email')}}"/></div>
                        <span class="has-error error" style="display:none;"> </span>
                    </div>
                </div>
                <div class="column">
                    <div class="row">
                        <label>Cidade <b>*</b></label>
                        <div class="field"> <input type="text" name="city" id="city" class="required" value="{{Helper::getKey($inputs, 'city')}}"/></div>
                        <span class="has-error error" style="display:none;"> </span>
                    </div>
                    <div class="row">
                        <label>Código Postal <b>*</b></label>
                        <div class="field"><input type="text" name="zip_code" class="required" id="zip_code" value="{{Helper::getKey($inputs, 'zip_code')}}"/></div>
                        <span class="has-error error" style="display:none;"> </span>
                    </div>
                    <div class="row">
                        <label>Cont. Telefónico <b>*</b></label>
                        <div class="field">  <input type="text" name="phone" id="phone" class="required" value="{{Helper::getKey($inputs, 'phone', '+351 ')}}"/></div>
                        <span class="has-error error" style="display:none;"> </span>
                    </div>
                </div>
            </div>
            <div class="header">CONTA CASINO PORTUGAL</div>
            <div class="row">
                <div class="column">
                    <div class="row">
                        <label>Nome de Utilizador <b>*</b></label>
                        <div class="field"><input type="text" name="username" id="username" class="required" value="{{Helper::getKey($inputs, 'username')}}"/></div>
                        <span class="has-error error" style="display:none;"> </span>
                    </div>
                    <div class="row">
                        <label>Palavra Passe <b>*</b></label>
                        <div class="field">  <input type="password" name="password" id="password" class="required"/></div>
                        <span class="has-error error" style="display:none;"> </span>
                    </div>
                    <div class="row">
                        <label>Repita Palavra Passe  <b>*</b></label>
                        <div class="field"><input type="password" name="conf_password" id="conf_password" class="required"/></div>
                        <span class="has-error error" style="display:none;"> </span>
                    </div>
                </div>
                <div class="column">
                    <div class="row">
                        <label>Código Pin <b>*</b></label>
                        <div class="field"><input size="4" maxlength="4" type="text" name="security_pin" id="security_pin" class="required" value="{{Helper::getKey($inputs, 'security_pin')}}"/></div>
                        <span class="has-error error" style="display:none;"> </span>
                    </div>
                    <div class="row">
                        <label>Código Promocional</label>
                        <div class="field"><input type="text" name="promo_code" id="promo_code" value="<?php echo Cookie::has('btag') ? Cookie::get('btag') : ''?>" readonly/></div>
                        <span class="has-error error" style="display:none;"> </span>
                    </div>
                    <div class="row">
                        <label>Código Amigo</label>
                        <div class="field"><input type="text" name="friend_code" id="friend_code" value="{{Helper::getKey($inputs, 'promo_code')}}"/></div>

                        <span class="has-error error" style="display:none;"> </span>
                    </div>
                </div>
            </div>
            <div class="header">CONTA DE PAGAMENTO</div>
            <div class="bs-wp">
                <div class="row bank">
                    <div class="col-xs-4">
                        <label>Nome do Banco</label>
                        <div class="field"><input type="text" name="bank_name" id="bank_name" value="{{Helper::getKey($inputs, 'bank_name')}}"/></div>
                        <span class="has-error error" style="display:none;"> </span>
                    </div>
                    <div class="col-xs-2">
                        <label>BIC/SWIFT</label>
                        <div class="field"><input type="text" name="bank_bic" id="bank_bic" value="{{Helper::getKey($inputs, 'bank_bic')}}"/></div>
                        <span class="has-error error" style="display:none;"> </span>
                    </div>
                    <div class="col-xs-6">
                        <label>IBAN</label>
                        <div class="field"><input type="text" name="bank_iban" id="bank_iban" value="{{Helper::getKey($inputs, 'bank_iban')}}"/></div>
                        <span class="has-error error" style="display:none;"> </span>
                    </div>
                </div>
                <div class="row">
                    <div id="summary"></div>
                </div>
                <div class="row agreement">
                    <div class="col-xs-12 error-placer">
                        <div class="check">
                            <input type="checkbox" name="general_conditions" id="general_conditions" class="required"/>
                        </div>
                        <label class="place">Li e estou de acordo com os <a target="_blank" href="/info/termos_e_condicoes">
                                termos e condições</a> e garanto ter no mínimo 18 anos.</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer">
            <div class="captcha">
                <div class="img">
                    <img src="{{$captcha['image_src']}}" alt="captcha" id="captcha-img">
                </div>
                <div class="codigo">
                    <div class="refresh">
                        <button class="btn btn-warning" id="captcha-refresh"><i class="cp-refresh"></i></button>
                    </div>
                    <div class="field"><input type="text" name="captcha" id="captcha" value="" autocomplete="off"/></div>
                    <span class="has-error error" style="display:none;"> </span>
                </div>
            </div>
            <div class="actions" style="margin-bottom:10px;">
                <button type="submit" class="submit formSubmit">VALIDAR</button>

                <button type="button" id="limpar">LIMPAR</button>
            </div>
        </div>
    </div>
    {!! Form::close() !!}
@stop
