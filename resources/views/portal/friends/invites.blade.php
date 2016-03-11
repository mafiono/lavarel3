@extends('layouts.portal', ['mini' => true])

@section('content')

        @include('portal.profile.head', ['active' => 'CONVIDAR AMIGOS'])

        @include('portal.friends.head_friends', ['active' => 'ENVIAR CONVITES'])

            {!! Form::open(array('route' => array('comunicacao/definicoes'),'id' => 'saveForm')) !!}
                <div class="col-xs-45 lin-xs-11 fleft">
                    <div class="box-form-user-info lin-xs-12">
                        <div class="title-form-registo brand-title brand-color aleft">
                            Convide os seus Amigos
                        </div>
                        
                        <div class="brand-descricao media-mbottom aleft">                                    
                            <b>Escolha o modo de contacto: Email, Twitter, Facebook</b>
                        </div>
                        <div class="convida_main media-mbottom acenter">
                            <ul>
                                <li id="convida_email" class="convida_email"></li>
                                <li id="convida_twitter" class="convida_twitter"></li>
                                <li id="convida_facebook" class="convida_facebook"></li>
                            </ul>
                        </div>
                        
                        <div class="brand-descricao media-mbottom aleft">                                    
                            <b>Aceda a uma das suas listas de endereços pessoais</b>
                        </div>
                        <div class="convida_listas media-mbottom acenter">
                            <ul>
                                <li class="col-xs-34 acenter"><img src="/assets/portal/img/gmail_logo.png" alt="gmail" /></li>
                                <li class="col-xs-34 acenter"><img src="/assets/portal/img/yahoo_logo.png" alt="yahoo" /></li>
                                <li class="col-xs-34 acenter"><img src="/assets/portal/img/hotmail_logo.png" alt="hotmail" /></li>
                            </ul>
                        </div>
                        <div class="brand-descricao aleft">                                    
                            <b>E/ou insira manualmente o Email dos seus Amigos, separados por virgula.</b>
                        </div>
                        <form>
                        <div class="micro-mtop media-mbottom">
                            <input class="col-xs-12" type="text" name="emails_list" />
                        </div>
                        <div class="brand-descricao aleft">                                    
                            <b>Escreva uma Mensagem Pessoal (Opcional).</b>
                        </div>
                        <div class="micro-mtop">
                            <textarea class="col-xs-12" rows="9" name="emails_list_message"></textarea>
                        </div>
                        <div class="micro-mtop">
                            <input type="submit" class="col-xs-4 brand-botao brand-link fright" value="Convidar" />
                        </div>
                        <div class="clear"></div>
                        
                        </form>

                    </div>
                </div>
                <div class="clear"></div>             
            {!! Form::close() !!}


        @include('portal.profile.bottom')
                        
@stop

@section('scripts')

    {!! HTML::script(URL::asset('/assets/portal/js/jquery.validate.js')); !!}    
    {!! HTML::script(URL::asset('/assets/portal/js/jquery.validate-additional-methods.js')); !!}
    {!! HTML::script(URL::asset('/assets/portal/js/plugins/jquery-form/jquery.form.min.js')); !!}
    {!! HTML::script(URL::asset('/assets/portal/js/forms.js')); !!}

    <script type="text/javascript">

        var convidaMain = $('.convida_main');
        var convidaMainLi = convidaMain.find('li');

        $(function(){
            convidaMainLi.on('click', function(){
                $.each(convidaMainLi, function(key, element){
                    $(element).removeClass($(element).prop('id')+'_active');
                });
                $(this).addClass($(this).prop('id')+'_active');
            });
        });

        var rules = {
            limite_apostas_diario: {
                number: true
            },
            limite_apostas_semanal: {
                number: true
            },        
            limite_apostas_mensal: {
                number: true
            },  
        };

        var messages = {
            limite_apostas_diario: {
                number: "Apenas dígitos são aceites no formato x.xx",
            },                
            limite_apostas_semanal: {
                number: "Apenas dígitos são aceites no formato x.xx",
            },                
            limite_apostas_mensal: {
                number: "Apenas dígitos são aceites no formato x.xx",
            }            
        };
            
    </script>

@stop