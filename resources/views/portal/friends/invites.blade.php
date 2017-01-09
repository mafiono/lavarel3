@extends('portal.profile.layout', [
    'active1' => 'promocoes',
    'middle' => 'portal.promotions.head_promotions',
    'active2' => 'convites',
    'form' => array('route' => array('amigos/convites'),'id' => 'saveForm'),
    'btn' => 'Convidar'])

@section('sub-content')
<div class="friends">
    <div class="row">
        <div class="col-xs-12">
            <div class="title">Convide os seus Amigos e ganhe bónus para ambos!</div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <div class="sub-title">1. Poderá faze-lo através das suas redes sociais favoritas...</div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 icons">
            <div class="facebook">
                <a href="javascript:void(0)">
                    <i class="fa fa-facebook"></i></a>
            </div>
            <div class="twitter">
                <a id="convida_twitter_link" class="convida_twitter" href="https://twitter.com/intent/tweet?button_hashtag=bet_portugal&text=Convite%20para%20jogar%20na" data-lang="pt" data-url="https://www.casinoportugal.pt">
                    <i class="fa fa-twitter"></i></a>
            </div>
            <div class="google-plus">
                <a href="javascript:void(0)">
                    <i class="fa fa-google-plus"></i></a>
            </div>
            <div class="linkedin">
                <a href="javascript:void(0)">
                    <i class="fa fa-linkedin"></i></a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <div class="sub-title">2. Acedendo ás suas listas de endereços pessoais de email...</div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 icons">
            <div class="gmail">
                <a href="javascript:void(0)">
                    <i class="fa fa-envelope"></i></a>
            </div>
            <div class="hotmail">
                <a href="javascript:void(0)">
                    <i class="fa fa-envelope"></i></a>
            </div>
            <div class="yahoo">
                <a href="javascript:void(0)">
                    <i class="fa fa-yahoo"></i></a>
            </div>
            <div class="sapo">
                <a href="mailto:?subject=Convite para jogar&body=Olá, %0D%0Avem jogar na BetPortugal (http:/%2Fbetportugal.pt).">
                    <i class="fa fa-envelope"></i></a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <div class="sub-title">3. Ou inserindo os emails dos seus Amigos, separados por virgula.</div>
        </div>
    </div>

    @include('portal.partials.input-text', [
        'field' => 'nome',
        'name' => '', //Indique os emails dos seus Amigos, separados por virgula',
        'hiddenLabel' => '',
        'value' => '',
    ])

    <div class="row">
        <div class="col-xs-12">
            <div class="sub-title">Também poderá escrever uma mensagem pessoal (opcional).</div>
        </div>
    </div>
    @include('portal.partials.input-text-area', [
        'field' => 'emails_list_message',
        'value' => 'Olá, &#13;&#10; vem jogar no Casino Portugal (https://www.casinoportugal.pt). &#13;&#10; Código Promocional: '. $authUser->user_code,
        'icon' => '',
        'required' => false
    ])
</div>

@stop

@section('scripts')
    <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
    <script src="https://apis.google.com/js/client.js"></script>
    <script src="https://js.live.net/v5.0/wl.js"></script>
    {!! HTML::script(URL::asset('/assets/portal/js/friends/contactsImporter.js')) !!}
    {!! HTML::script(URL::asset('/assets/portal/js/friends/friendsNetwork.js')) !!}
@stop
