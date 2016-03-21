<div class="box-links">
    <div class="fcenter title-form-registo brand-title brand-color aleft">
        Opções de Utilizador
    </div>
    <?php
    $menu = [
       ['name' => 'PERFIL', 'link' => '/perfil'],
       ['name' => 'BANCO', 'link' => '/banco/saldo'],
       ['name' => 'PROMOÇÕES', 'link' => '/promocoes'],
       ['name' => 'COMUNICAÇÃO', 'link' => '/comunicacao/definicoes'],
       ['name' => 'PROMOÇÕES', 'link' => '/promocoes'],
       ['name' => 'CONVIDAR AMIGOS', 'link' => '/amigos'],
       ['name' => 'HISTÓRICO', 'link' => '/historico'],
       ['name' => 'JOGO RESPONSÁVEL', 'link' => '/jogo-responsavel'],
    ]; ?>
    @foreach($menu as $item)
        <div class="col-lg-12 div-link">
            <a class="btn btn-menu brand-trans {{$active==$item['name']?'sel':''}}"
               href="{{$item['link']}}">{{$item['name']}}</a>
        </div>
    @endforeach
</div>