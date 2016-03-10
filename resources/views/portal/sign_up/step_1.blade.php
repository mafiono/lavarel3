@extends('layouts.portal')


@section('content')

    <div class="col-xs-12 home-back">
        <div class="main-contend main-opacity">
            <div class="main white-back">
                <div class="brand-back brand-box-title">
                    <div class="col-xs-2 main-logo fleft">
                        <img alt="ibetup" src="/assets/portal/img/main_logo.png" />
                    </div>
                    <div class="col-xs-10 brand-title aright white-color fleft">
                        Em menos de <b>1 minuto</b> estará a jogar!
                        <a href="/" class="btn menu-black-active"><i class="fa fa-close"></i></a>
                    </div>
                    <div class="clear"></div>
                </div>
                
                @include('portal.messages')

                <div class="form-registo">
                    <div class="col-xs-2 lin-xs-12 fleft">
                        <div class="lin-xs-12 banner-back box-form-registo">
                            <img src="/assets/portal/img/banners/banner_ronaldo.png" alt="Banner Registo">
                        </div>
                    </div>
                    <div class="col-xs-10 lin-xs-12 fleft">
                        {!! Form::open(array('route' => array('registar/step1'),'id' => 'saveForm')) !!}
                            <div class="col-xs-4 lin-xs-12 fleft">
                                <div class="box-form-registo border-form-registo lin-xs-12">
                                    <div class="title-form-registo brand-title brand-color aleft">
                                        Informação Pessoal
                                    </div>
                                    
                                    <div class="registo-form">
                                        <label>Título</label>
                                        <select name="gender" id="gender">
                                            @if (!empty($inputs) && $inputs['gender'] == 'f')
                                                <option value="m">Sr.</option>
                                                <option value="f" selected>Sra.</option>
                                            @else
                                                <option value="m" selected>Sr.</option>
                                                <option value="f">Sra.</option>
                                            @endif
                                        </select>
                                        <span class="has-error error" style="display:none;"> </span>
                                    </div>
                                    
                                    <div class="registo-form">
                                        <label>Nome Completo</label>
                                        <input type="text" name="name" id="name" class="required" value="<?php echo !empty($inputs) ? $inputs['name'] : ''?>"/>
                                        <span class="has-error error" style="display:none;"> </span>
                                    </div>
                                    
                                    <div class="registo-form">
                                        <label>Data de Nascimento</label>
                                        <div class="fleft">
                                            <select name="age_day">
                                                <?php for ($i=1; $i < 32; $i++): ?>
                                                    <option value='{{$i}}' <?php echo !empty($inputs) && $inputs['age_day'] == $i ? 'selected'  : ''?>><?php echo sprintf("%02d",$i)?></option>        
                                                <?php endfor?>
                                            </select>                                               
                                            <select name="age_month">
                                                <?php for ($i=1; $i < 13; $i++): ?>
                                                    <option value='{{$i}}' <?php echo !empty($inputs) && $inputs['age_month'] == $i ? 'selected'  : ''?>><?php echo sprintf("%02d",$i)?></option>        
                                                <?php endfor?>
                                            </select>
                                            <select name="age_year">
                                                <?php
                                                $year = \Carbon\Carbon::now()->subYears(18)->year;
                                                // $year = date("Y") - 18;
                                                for ($i=$year; $i > $year - 95; $i--): ?>
                                                    <option value='{{$i}}' <?php echo !empty($inputs) && $inputs['age_year'] == $i ? 'selected'  : ''?>>{{$i}}</option>        
                                                <?php endfor?>
                                            </select>
                                            <input name="birth_date" type="hidden">
                                            <span class="has-error error" style="display:none;"> </span>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                    
                                    <div class="registo-form">
                                        <label>Nacionalidade</label>
                                        {!! Form::select('nationality', $natList, !empty($inputs) ? $inputs['nationality'] : 'Português', ['style' => 'width:73%;']) !!}
                                        <span class="has-error error" style="display:none;"> </span>
                                    </div>
                                    
                                    <div class="registo-form registo-form-costumized" style="height: 90px;">
                                        <label>Nº Identificação civil</label>
                                        <input type="text" name="document_number" id="document_number" class="required" value="<?php echo !empty($inputs) ? $inputs['document_number'] : ''?>"/>
                                        <span class="has-error error" style="display:none;"> </span>
                                        <p>Bi, Cartão Cidadão, Passaporte, Carta de Condução</p>
                                    </div>
                                    
                                    <div class="registo-form">
                                        <label>NIF</label>
                                        <input type="text" name="tax_number" id="tax_number" class="required" value="<?php echo !empty($inputs) ? $inputs['tax_number'] : ''?>"/>
                                        <span class="has-error error" style="display:none;"> </span>
                                    </div>
                                    
                                    <div class="registo-form">
                                        <label>Profissão</label>
                                        <input type="text" name="profession" id="profession" class="required" value="<?php echo !empty($inputs) ? $inputs['profession'] : ''?>"/>
                                        <span class="has-error error" style="display:none;"> </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-4 lin-xs-12 fleft">
                                <div class="box-form-registo border-form-registo lin-xs-12">
                                    <div class="title-form-registo brand-title brand-color aleft">
                                        Contactos
                                    </div>
                                    
                                    <div class="registo-form">
                                        <label>Pais</label>
                                        {!! Form::select('country', $countryList, !empty($inputs) ? $inputs['country'] : 'Portugal', ['style' => 'width:73%;']) !!}
                                        <span class="has-error error" style="display:none;"> </span>
                                    </div>
                                    
                                    <div class="registo-form">
                                        <label>Morada</label>
                                        <input type="text" name="address" id="address" class="required" value="<?php echo !empty($inputs) ? $inputs['address'] : ''?>"/>
                                        <span class="has-error error" style="display:none;"> </span>
                                    </div>
                                    
                                    <div class="registo-form">
                                        <label>Cidade</label>
                                        <input type="text" name="city" id="city" class="required" value="<?php echo !empty($inputs) ? $inputs['city'] : ''?>"/>
                                        <span class="has-error error" style="display:none;"> </span>
                                    </div>
    
                                    <div class="registo-form">
                                        <label><b style="color:#777;">Código Postal</b></label>
                                        <input type="text" name="zip_code" class="required" id="zip_code" value="<?php echo !empty($inputs) ? $inputs['zip_code'] : ''?>"/>
                                        <span class="has-error error" style="display:none;"> </span>
                                    </div>
                                    
                                    <div class="registo-form">
                                        <label>Email</label>
                                        <input type="email" name="email" id="email" class="required" value="<?php echo !empty($inputs) ? $inputs['email'] : ''?>"/>
                                        <span class="has-error error" style="display:none;"> </span>
                                    </div>
                                    
                                    <div class="registo-form">
                                        <label>Confirmação Email</label>
                                        <input type="email" name="conf_email" id="conf_email" class="required"/>
                                        <span class="has-error error" style="display:none;"> </span>
                                    </div>
    
                                    <div class="registo-form">
                                        <label>Telefone</label>
                                        <input type="text" name="phone" id="phone" class="required" value="<?php echo !empty($inputs) ? $inputs['phone'] : ''?>"/>
                                        <span class="has-error error" style="display:none;"> </span>
                                    </div>
                                    
                                </div>
                            </div>
                            <div class="col-xs-4 lin-xs-12 fleft">
                                <div class="box-form-registo lin-xs-12">
                                    <div class="title-form-registo brand-title brand-color aleft">
                                        Detalhe de Conta
                                    </div>
                                    
                                    <div class="registo-form">
                                        <label>Username</label>
                                        <input type="text" name="username" id="username" class="required" value="<?php echo !empty($inputs) ? $inputs['username'] : ''?>"/>
                                        <span class="has-error error" style="display:none;"> </span>
                                    </div>
    
                                    <div class="registo-form">
                                        <label>Password</label>
                                        <input type="password" name="password" id="password" class="required"/>
                                        <span class="has-error error" style="display:none;"> </span>
                                    </div>
    
                                    <div class="registo-form">
                                        <label>Confirmação Password</label>
                                        <input type="password" name="conf_password" id="conf_password" class="required"/>
                                        <span class="has-error error" style="display:none;"> </span>
                                    </div>
                                    
                                    <div class="registo-form-costumized">
                                        <label class="brand-color"><b>Código PIN</b> o seu Código de Segurança</label>
                                        <input type="text" name="security_pin" id="security_pin" class="required" style="width:70%"/>
                                        <span class="has-error error" style="display:none;"> </span>
                                    </div>
                                    
                                    <div class="registo-form">
                                        <label>Moeda</label>
                                        <select name="currency" style="width:73%;">
                                            <option value="euro" selected="selected">EURO</option>
                                        </select>
                                        <span class="has-error error" style="display:none;"> </span>
                                    </div>
                                    
                                    <div class="registo-form">
                                        <label>Promo Code</label>
                                        <input type="text" name="promo_code"  value="<?php echo !empty($inputs) ? $inputs['promo_code'] : ''?>"/>
                                        <span class="has-error error" style="display:none;"> </span>
                                    </div>
    
                                    <div class="registo-form-costumized">
                                        <p><input type="checkbox" name="general_conditions" id="general_conditions" class="required"/>
                                        Tenho pelo menos 18 anos e aceito os <a target="_blank" href="/info/termos_e_condicoes" onclick="onPopup(this); return false;" class="brand-color brand-link">Termos</a> e <a target="_blank" href="/info/termos_e_condicoes" onclick="onPopup(this); return false;" class="brand-color brand-link">Condições</a> e <a target="_blank" href="/info/regras" onclick="onPopup(this); return false;" class="brand-color brand-link">Regras</a>.</p>
                                        <span class="has-error error" style="display:none;"> </span>
                                        <div class="clear"></div>
                                    </div>

                                </div>                                
                            </div>
                            <div class="clear"></div>

                            @include('portal.sign_up.footer', ['step' => 1])
                        {!! Form::close() !!}
                    </div>
                    <div class="clear"></div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('scripts')

    {!! HTML::script(URL::asset('/assets/portal/js/jquery.validate.js')); !!}    
    {!! HTML::script(URL::asset('/assets/portal/js/jquery.validate-additional-methods.js')); !!}
    {!! HTML::script(URL::asset('/assets/portal/js/plugins/jquery-form/jquery.form.min.js')); !!}
    {!! HTML::script(URL::asset('/assets/portal/js/forms.js')); !!}
    {!! HTML::script(URL::asset('/assets/portal/js/plugins/rx.umd.min.js')) !!}

    {!! HTML::script(URL::asset('/assets/portal/js/registo/step1.js')); !!}

@stop