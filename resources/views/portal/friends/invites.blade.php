@extends('portal.profile.layout', [
    'active1' => 'convidar',
    'middle' => 'portal.friends.head_friends',
    'active2' => 'convites',
    'form' => array('route' => array('amigos/convites'),'id' => 'saveForm'),
    'btn' => 'Convidar'])

@section('sub-content')

<div class="col-xs-12 fleft">
    <div class="title-form-registo brand-title brand-color aleft">
        Convide os seus Amigos
    </div>

    <div class="brand-descricao mini-mbottom aleft">
        <b>Escolha o modo de contacto: Email, Twitter, Facebook </b>
    </div>
    <div class="convida_main mini-mbottom acenter">
        <ul>
            <li id="convida_email" >
                <a class="convida_email" href="mailto:?subject=Convite para jogar&body=Olá, %0D%0Avem jogar na BetPortugal (http:/%2Fbetportugal.pt)."></a>
            </li>
            <li id="convida_twitter">
                <a id="convida_twitter_link" class="convida_twitter" href="https://twitter.com/intent/tweet?button_hashtag=bet_portugal&text=Convite%20para%20jogar%20na" data-lang="pt" data-url="https://www.casinoportugal.pt"></a>
                <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
            </li>
            <li id="convida_facebook" class="convida_facebook"></li>
        </ul>
    </div>

    <div class="brand-descricao mini-mbottom aleft">
        <b>Aceda a uma das suas listas de endereços pessoais</b>
    </div>
    <div class="convida_listas mini-mbottom acenter">
        <ul>
            <li id="import_gmail_contacts" class="col-xs-34 acenter"><img src="/assets/portal/img/gmail_logo.png" alt="gmail" /></li>
            {{--<li id="import_yahoo_contacts" class="col-xs-34 acenter"><img src="/assets/portal/img/yahoo_logo.png" alt="yahoo" /></li>--}}
            <li id="import_live_contacts" class="col-xs-34 acenter"><img src="/assets/portal/img/hotmail_logo.png" alt="hotmail" /></li>
        </ul>
    </div>
    <div class="brand-descricao aleft">
        <b>E/ou insira manualmente o Email dos seus Amigos, separados por virgula.</b>
    </div>
    <div class="micro-mtop mini-mbottom">
        {!! Form::text('emails_list', null, ['class' => 'col-xs-12', 'id' => 'emails_list']) !!}
    </div>
    <div class="brand-descricao aleft">
        <b>Escreva uma Mensagem Pessoal (Opcional).</b>
    </div>
    <div class="micro-mtop">
    {!! Form::textarea('emails_list_message', 'Olá, &#13;&#10; vem jogar no Casino Portugal (https://www.casinoportugal.pt). &#13;&#10; Código Promocional: '.
        $authUser->user_code,
     ['rows' => '8', 'id' => 'emails_list_message']) !!}
    </div>
    <div class="clear"></div>
</div>

@stop

@section('scripts')
    <script src="https://apis.google.com/js/client.js"></script>
    <script src="https://js.live.net/v5.0/wl.js"></script>
    <script src="/assets/portal/js/friends/contactsImporter.js"></script>
    {!! HTML::script(URL::asset('/assets/portal/js/friends/friendsNetwork.js')) !!}
@stop
