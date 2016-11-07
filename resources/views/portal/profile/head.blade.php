
    <?php
        if(\App\Lib\Notifications::getMensagens()>0)
            {
    $menu = [
       ['key' => 'perfil','name' => 'PERFIL', 'link' => '/perfil','class' => 'small'],
       ['key' => 'banco','name' => 'BANCO', 'link' => '/banco/saldo','class' => 'small'],
       ['key' => 'promocoes','name' => 'BÓNUS', 'link' => '/promocoes','class' => 'small'],
            ['key' => 'historico','name' => 'HISTÓRICO', 'link' => '/historico','class' => 'small'],
       ['key' => 'comunicacao','name' => 'COMUNICAÇÃO', 'link' => '/comunicacao/definicoes', 'count' => \App\Lib\Notifications::getMensagens(),'class' => 'big'],
       ['key' => 'jogo_responsavel','name' => 'JOGO RESPONSÁVEL', 'link' => '/jogo-responsavel/limites/apostas','class' => 'big'],
    ]; }else{
            $menu = [
                    ['key' => 'perfil','name' => 'PERFIL', 'link' => '/perfil','class' => 'small'],
                    ['key' => 'banco','name' => 'BANCO', 'link' => '/banco/saldo','class' => 'small'],
                    ['key' => 'promocoes','name' => 'BÓNUS', 'link' => '/promocoes','class' => 'small'],
                    ['key' => 'historico','name' => 'HISTÓRICO', 'link' => '/historico','class' => 'small'],
                    ['key' => 'comunicacao','name' => 'COMUNICAÇÃO', 'link' => '/comunicacao/definicoes', 'count' => \App\Lib\Notifications::getMensagens(),'class' => 'big'],
                    ['key' => 'jogo_responsavel','name' => 'JOGO RESPONSÁVEL', 'link' => '/jogo-responsavel/limites/apostas','class' => 'big'],
            ];
        }
    ?>
    @foreach($menu as $item)
        <div class="bloco {{$item['class']}}">
            <a class="btn btn-menu brand-trans {{$active==$item['key']?'sel':''}}"
               href="{{$item['link']}}">{{$item['name']}}
              </a>

        </div>
    @endforeach
