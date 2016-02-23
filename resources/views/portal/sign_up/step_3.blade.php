@extends('layouts.portal')


@section('content')

<!---- CONTEND ---->
    <div class="col-xs-12 home-back">
        <div class="main-contend main-opacity">
            <div class="main white-back">
                <div class="brand-back brand-box-title">
                    <div class="col-xs-2 main-logo fleft">
                        <img alt="ibetup" src="/assets/portal/img/main_logo.png" />
                    </div>
                    <div class="col-xs-10 brand-title aright white-color fleft">
                        Em menos de <b>1 minuto</b> estará a jogar!
                    </div>
                    <div class="clear"></div>
                </div>
                
                <div class="form-registo">
                    <div class="col-xs-2 lin-xs-12 fleft">
                        <div class="lin-xs-12 brand-back box-form-registo">
                            Banner Registo
                        </div>
                    </div>
                    <div class="col-xs-10 lin-xs-12 fleft">
                        {!! Form::open(array('route' => array('/registar/step3'),'id' => 'saveForm', 'class' => 'lin-xs-9')) !!}
                            <div class="col-xs-4 lin-xs-12 fleft">
                                <div class="box-form-registo border-form-registo lin-xs-12">
                                    <div class="title-form-registo brand-title brand-color aleft">
                                        Conta de Pagamento
                                    </div>
                                    
                                    <div class="col-xs-11 brand-descricao aleft" style="margin-bottom:40px;">                                    
                                        Por favor indique a conta para que pretende como destinatária de futuros levantamentos.
                                    </div>

                                    <div class="registo-form">
                                        <label>Banco</label>
                                        <input type="text" name="bank" id="bank" class="required" />
                                        <span></span>
                                    </div>
                                        
                                    <div class="registo-form">
                                        <label>Iban</label>
                                        <span style="display: inline">PT50</span><input type="text" name="iban" id="iban" class="required" />
                                        <span></span>
                                    </div>

                                    <div class="registo-form" >
                                        <label>Comprovativo</label>
                                        <input type="file" id="upload" name="upload" class="required col-xs-6 brand-botao brand-link upload-input" />
                                        <div class="clear"></div>
                                    </div>

                                    <div class="col-xs-9 form-submit aright fright">
                                        <input type="submit" class="col-xs-8 fleft brand-botao brand-link formSubmit" value="Continuar" />
                                    </div>
                                    <div class="clear"></div>
                                </div>

                            </div>
                            <div class="col-xs-8 lin-xs-12 fleft">
                                <div class="box-form-registo lin-xs-12">
                                    <div class="title-form-registo brand-title brand-color aleft">
                                        Deposite Já!
                                    </div>
                                    

                                </div>                                
                            </div>
                        {!! Form::close() !!}
                        <div class="form-rodape">
                            <div class="col-xs-3 fleft">&nbsp;</div>
                            <div class="col-xs-6 form-marcadores acenter fleft">
                                <p>1</p>
                                <p>2</p>
                                <p class="brand-botao">3</p>
                                <p>4</p>
                            </div>
                            <div class="col-xs-3 acenter fleft">
                                <a href="/registar/step4" class="">
                                    <div class="brand-botao-rev2 brand-trans brand-botao">Completar mais tarde...</div>
                                </a>
                            </div>
                            <div class="clear"></div>
                        </div>
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

    {!! HTML::script(URL::asset('/assets/portal/js/registo/step3.js')); !!}

@stop