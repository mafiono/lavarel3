@extends('layouts.portal', ['mini' => true])

@section('content')

        @include('portal.profile.head', ['active' => 'CONVIDAR AMIGOS'])

        @include('portal.friends.head_friends', ['active' => 'ENVIAR CONVITES'])

                <div class="col-xs-45 lin-xs-11 fleft">
                    <div class="box-form-user-info lin-xs-12">
                        <div class="title-form-registo brand-title brand-color aleft">
                            Convide os seus Amigos
                        </div>
                        
                        <div class="brand-descricao media-mbottom aleft">                                    
                            <b>Escolha o modo de contacto: Email, Twitter, Facebook </b>
                        </div>
                        <div class="convida_main media-mbottom acenter">
                            <ul>
                                <li id="convida_email" >
                                    <a class="convida_email" href="mailto:?subject=Convite para jogar&body=Olá, %0D%0Avem jogar na BetPortugal (http://casino.ibetup.eu)."></a>
                                </li>
                                <li id="convida_twitter">
                                    <a id="convida_twitter_link" class="convida_twitter" href="https://twitter.com/intent/tweet?button_hashtag=BetPortugal&text=Convite%20para%20jogar%20na" data-lang="pt" data-url="http://casino.ibetup.eu"></a>
                                    <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
                                </li>
                                <li id="convida_facebook" class="convida_facebook">
                                    <script>

                                    </script>
                                </li>
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
                        {!! Form::open(['route' => array('amigos/convites'), 'id' => 'saveForm']) !!}
                        <div class="micro-mtop media-mbottom">
                            {!! Form::text('emails_list', null, ['class' => 'col-xs-11', 'id' => 'emails_list']) !!}
                        </div>
                        <div class="brand-descricao aleft">                                    
                            <b>Escreva uma Mensagem Pessoal (Opcional).</b>
                        </div>
                        <div class="micro-mtop">
                            {!! Form::textarea('emails_list_message', 'Olá, &#13;&#10; vem jogar na BetPortugal (http://casino.ibetup.eu).', ['class' => 'col-xs-11', 'rows' => '8', 'id' => 'emails_list_message']) !!}
                        </div>
                        <div class="micro-mtop">
                            <div style="float: left;">
                                @include('portal.messages')
                            </div>
                            {!! Form::submit('Convidar', ['class' => 'col-xs-4 brand-botao brand-link fright formSubmit']) !!}
                        </div>
                        <div class="clear"></div>
                        
                        {!! Form::close() !!}

                    </div>
                </div>
                <div class="clear"></div>

        @include('portal.profile.bottom')
                        
@stop

@section('scripts')

    {!! HTML::script(URL::asset('/assets/portal/js/jquery.validate.js')) !!}
    {!! HTML::script(URL::asset('/assets/portal/js/jquery.validate-additional-methods.js')) !!}
    {!! HTML::script(URL::asset('/assets/portal/js/plugins/jquery-form/jquery.form.min.js')) !!}
    {!! HTML::script(URL::asset('/assets/portal/js/forms.js')) !!}
    {!! HTML::script(URL::asset('/assets/portal/js/forms.js')) !!}
    {!! HTML::script(URL::asset('/assets/portal/js/friends/friendsNetwork.js')) !!}

@stop
