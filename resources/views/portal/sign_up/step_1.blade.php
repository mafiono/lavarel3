@extends('portal.sign_up.register')

@section('content')
    {!! Form::open(array('route' => array('registar/step1'),'id' => 'saveForm')) !!}
    <div class="register">
        <div class="title">
            Está a 3 passos de começar a apostar!
            <i id="register-close" class="fa fa-times"></i>
        </div>
        <div class="content">
            <div align="center" style="margin-top:10px">
                <div class="breadcrumb flat">
                    <a href="#" class="active">1. REGISTAR</a>
                    <a href="#">2. VALIDAR</a>
                    <a href="#">3. DEPOSITAR</a>
                    <a href="#">e</a>
                </div>
            </div>
            <div class="header">DADOS PESSOAIS</div>
            <div class="row">
                <div class="column">
                    <div class="row">
                        <label>Título</label>
                        <div class="field top">
                            <input name="gender" id="gender" value="m" type="radio"> Sr. &nbsp;
                            <input name="gender" value="f" type="radio" > Sr.ª
                            <span class="has-error error" style="display:none;"> </span>
                        </div>
                    </div>
                    <div class="row">
                        <label>Primeiro Nome</label>
                        <div class="field"><input name="firstname" id="firstname" class="required" type="text" value="<?php echo !empty($inputs) ? $inputs['firstname'] : ''?>"></div>
                        <span class="has-error error" style="display:none;"> </span>
                    </div>
                    <div class="row">
                        <label>Apelidos</label>
                        <div class="field"><input name="name" id="name" type="text" value="<?php echo !empty($inputs) ? $inputs['name'] : ''?>" > <span class="has-error error"> </span> </div>
                    </div>
                    <div class="row">
                        <label>Identificação Civil</label>
                        <div class="field" ><input type="text" name="document_number" id="document_number" class="required" value="<?php echo !empty($inputs) ? $inputs['document_number'] : ''?>"/></div>
                        <span class="has-error error" style="display:none;"> </span>
                    </div>
                </div>
                <div class="column">
                    <div class="row">
                        <label>Data de Nascimento</label>
                        <div class="field birth-date">
                            <input name="birth_date" id="birth_date" type="hidden">
                            <select name="age_day"><?php for ($i=1; $i < 32; $i++): ?>
                                <option value='{{$i}}' <?php echo !empty($inputs) && $inputs['age_day'] == $i ? 'selected'  : ''?>><?php echo sprintf("%02d",$i)?></option>
                                <?php endfor?></select>
                            <select name="age_month"> <?php for ($i=1; $i < 13; $i++): ?>
                                <option value='{{$i}}' <?php echo !empty($inputs) && $inputs['age_month'] == $i ? 'selected'  : ''?>><?php echo sprintf("%02d",$i)?></option>
                                <?php endfor?></select>
                            <select name="age_year"><?php
                                $year = \Carbon\Carbon::now()->subYears(18)->year;
                                // $year = date("Y") - 18;
                                for ($i=$year; $i > $year - 95; $i--): ?>
                                <option value='{{$i}}' <?php echo !empty($inputs) && $inputs['age_year'] == $i ? 'selected'  : ''?>>{{$i}}</option>
                                <?php endfor?></select>
                            <span class="has-error error" style="display:none;"> </span>
                        </div>
                    </div>
                    <div class="row">
                        <label>Nacionalidade</label>
                        <div class="field">
                            <select id="nationality" name="nationality">
                                <option selected disabled>-</option>
                                <option value="PT">{{$natList['PT']}}</option>
                                @foreach($natList as $key => $country)
                                    <option value="{{$key}}" {{ !empty($inputs) && $inputs['nationality'] == $country ? 'selected'  : ''}}> {{$country}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <label>Ocupação</label>
                        <div class="field">
                            <select id="sitprofession" name="sitprofession">
                                @foreach($sitProfList as $key => $prof)
                                    <option value="{{$key}}" {{ !empty($inputs) && $inputs['sitprofession'] == $prof ? 'selected'  : ''}}>{{$prof}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <label>Número Fiscal</label>
                        <div class="field"><input type="text" name="tax_number" id="tax_number" class="required" value="<?php echo !empty($inputs) ? $inputs['tax_number'] : ''?>"/></div>
                        <span class="has-error error" style="display:none;"> </span>
                    </div>
                </div>
            </div>
            <div class="header">CONTACTOS</div>
            <div class="row">
                <div class="column">
                    <div class="row">
                        <label>País</label>
                        <div class="field">
                            <select id="country" name="country">
                                <option selected disabled>-</option>
                                <option value="PT">{{$countryList['PT']}}</option>
                                @foreach($countryList as $key => $country)
                                    <option value="{{$key}}" {{ !empty($inputs) && $inputs['nationality'] == $country ? 'selected'  : ''}}> {{$country}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <label>Morada</label>
                        <div class="field"><input type="text" name="address" id="address" class="required" value="<?php echo !empty($inputs) ? $inputs['address'] : ''?>"/></div>
                        <span class="has-error error" style="display:none;"> </span>
                    </div>
                    <div class="row">
                        <label>Email</label>
                        <div class="field"><input type="email" name="email" id="email" class="required" value="<?php echo !empty($inputs) ? $inputs['email'] : ''?>"/></div>
                        <span class="has-error error" style="display:none;"> </span>
                    </div>
                    <div class="row">
                        <label>Repita Email</label>
                        <div class="field"> <input type="email" name="conf_email" id="conf_email" class="required" value="<?php echo !empty($inputs) ? $inputs['email'] : ''?>"/></div>
                        <span class="has-error error" style="display:none;"> </span>
                    </div>
                </div>
                <div class="column">
                    <div class="row">
                        <label>Cidade</label>
                        <div class="field"> <input type="text" name="city" id="city" class="required" value="<?php echo !empty($inputs) ? $inputs['city'] : ''?>"/></div>
                        <span class="has-error error" style="display:none;"> </span>
                    </div>
                    <div class="row">
                        <label>Código Postal</label>
                        <div class="field"><input type="text" name="zip_code" class="required" id="zip_code" value="<?php echo !empty($inputs) ? $inputs['zip_code'] : ''?>"/></div>
                        <span class="has-error error" style="display:none;"> </span>
                    </div>
                    <div class="row">
                        <label>Cont. Telefónico</label>
                        <div class="field">  <input type="text" name="phone" id="phone" class="required" value="<?php echo !empty($inputs) ? $inputs['phone'] : '+351'?>"/></div>
                        <span class="has-error error" style="display:none;"> </span>
                    </div>
                </div>
            </div>
            <div class="header">CONTA CASINO PORTUGAL</div>
            <div class="row">
                <div class="column">
                    <div class="row">
                        <label>Nome de Utilizador</label>
                        <div class="field"><input type="text" name="username" id="username" class="required" value="<?php echo !empty($inputs) ? $inputs['username'] : ''?>"/></div>
                        <span class="has-error error" style="display:none;"> </span>
                    </div>
                    <div class="row">
                        <label>Palavra Passe</label>
                        <div class="field">  <input type="password" name="password" id="password" class="required"/></div>
                        <span class="has-error error" style="display:none;"> </span>
                    </div>
                    <div class="row">
                        <label>Repita Palavra Passe </label>
                        <div class="field"><input type="password" name="conf_password" id="conf_password" class="required"/></div>
                        <span class="has-error error" style="display:none;"> </span>
                    </div>
                </div>
                <div class="column">
                    <div class="row">
                        <label>Código Pin</label>
                        <div class="field"><input size="4" maxlength="4" type="text" name="security_pin" id="security_pin" class="required" value="<?php echo !empty($inputs) ? $inputs['security_pin'] : ''?>"/></div>
                        <span class="has-error error" style="display:none;"> </span>
                    </div>
                    <div class="row">
                        <label>Código Promocional</label>
                        <div class="field"><input type="text" name="promo_code"  value="<?php echo Cookie::has('btag') ? Cookie::get('btag') : ''?>"/></div>

                        <span class="has-error error" style="display:none;"> </span>
                    </div>
                    <div class="row">
                        <label>Código Amigo</label>
                        <div class="field"><input type="text" name="friend_code"  value="<?php echo !empty($inputs) ? $inputs['promo_code'] : ''?>"/></div>

                        <span class="has-error error" style="display:none;"> </span>
                    </div>
                </div>
            </div>
            <div class="header">CONTA DE PAGAMENTO</div>
            <div class="bs-wp">
                <div class="row bank">
                    <div class="col-xs-4">
                        <label>Nome do Banco</label>
                        <div class="field"><input type="text" name="bank_name" id="bank_name" value="<?php echo !empty($inputs) ? $inputs['bank_name'] : ''?>"/></div>
                        <span class="has-error error" style="display:none;"> </span>
                    </div>
                    <div class="col-xs-2">
                        <label>BIC/SWIFT</label>
                        <div class="field"><input type="text" name="bank_bic" id="bank_bic" value="<?php echo !empty($inputs) ? $inputs['bank_bic'] : ''?>"/></div>
                        <span class="has-error error" style="display:none;"> </span>
                    </div>
                    <div class="col-xs-6">
                        <label>IBAN</label>
                        <div class="field"><input type="text" name="bank_iban" id="bank_iban" value="<?php echo !empty($inputs) ? $inputs['bank_iban'] : ''?>"/></div>
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
                        <button class="btn btn-warning" id="captcha-refresh"><i class="fa fa-refresh"></i></button>
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
