@extends('portal.profile.layout', [
    'active1' => 'convidar',
    'middle' => 'portal.friends.head_friends',
    'active2' => 'convites'])

@section('sub-content')

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
                                    <a class="convida_email" href="mailto:?subject=Convite para jogar&body=Olá, %0D%0Avem jogar na BetPortugal (http:/%2Fbetportugal.pt)."></a>
                                </li>
                                <li id="convida_twitter">
                                    <a id="convida_twitter_link" class="convida_twitter" href="https://twitter.com/intent/tweet?button_hashtag=bet_portugal&text=Convite%20para%20jogar%20na" data-lang="pt" data-url="http://betportugal.pt"></a>
                                    <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
                                </li>
                                <li id="convida_facebook" class="convida_facebook"></li>
                            </ul>
                        </div>

                        <div class="brand-descricao media-mbottom aleft">                                    
                            <b>Aceda a uma das suas listas de endereços pessoais</b>
                        </div>
                        <div class="convida_listas media-mbottom acenter">
                            <ul>
                                <li id="import_gmail_contacts" class="col-xs-34 acenter"><img src="/assets/portal/img/gmail_logo.png" alt="gmail" /></li>
                                <li id="import_live_contacts" class="col-xs-34 acenter"><img src="/assets/portal/img/yahoo_logo.png" alt="yahoo" /></li>
                                <li id="import_yahoo_contacts" class="col-xs-34 acenter"><img src="/assets/portal/img/hotmail_logo.png" alt="hotmail" /></li>
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
                            {!! Form::textarea('emails_list_message', 'Olá, &#13;&#10; vem jogar na BetPortugal (http://betportugal.pt).', ['class' => 'col-xs-11', 'rows' => '8', 'id' => 'emails_list_message']) !!}
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

@stop

@section('scripts')
    <script src="https://apis.google.com/js/client.js"></script>
    <script src="https://js.live.net/v5.0/wl.js"></script>
    <script src="/assets/portal/js/friends/contactsImporter.js"></script>

    {!! HTML::script(URL::asset('/assets/portal/js/jquery.validate.js')) !!}
    {!! HTML::script(URL::asset('/assets/portal/js/jquery.validate-additional-methods.js')) !!}
    {!! HTML::script(URL::asset('/assets/portal/js/plugins/jquery-form/jquery.form.min.js')) !!}
    {!! HTML::script(URL::asset('/assets/portal/js/forms.js')) !!}
    {!! HTML::script(URL::asset('/assets/portal/js/forms.js')) !!}
    {!! HTML::script(URL::asset('/assets/portal/js/friends/friendsNetwork.js')) !!}

@stop
