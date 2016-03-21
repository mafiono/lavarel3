<div class="box-links">
    <div class="fcenter title-form-registo brand-title brand-color aleft">
        Opções de Utilizador
    </div>
    <?php
    $menu = [
       ['key' => 'perfil','name' => 'Perfil', 'link' => '/perfil'],
       ['key' => 'banco','name' => 'Banco', 'link' => '/banco/saldo'],
       ['key' => 'promocoes','name' => 'Promoções', 'link' => '/promocoes'],
       ['key' => 'comunicacao','name' => 'Comunicação', 'link' => '/comunicacao/definicoes'],
       ['key' => 'convidar ','name' => 'Convidar amigos', 'link' => '/amigos'],
       ['key' => 'historico','name' => 'Histórico', 'link' => '/historico'],
       ['key' => 'jogo ','name' => 'Jogo responsável', 'link' => '/jogo-responsavel'],
    ]; ?>
    @foreach($menu as $item)
        <div class="col-lg-12 div-link">
            <a class="btn btn-menu brand-trans {{$active==$item['key']?'sel':''}}"
               href="{{$item['link']}}">{{$item['name']}}</a>
        </div>
    @endforeach
</div>