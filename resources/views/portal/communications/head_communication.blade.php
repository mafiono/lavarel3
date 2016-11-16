<div class="box-links">
    <?php
    if (\App\Lib\Notifications::getMensagens() > 0) {
        $menu = [
                ['key' => 'mensagens', 'name' => 'Mensagens', 'link' => '/comunicacao/mensagens', 'count' => \App\Lib\Notifications::getMensagens()],
                ['key' => 'definicoes', 'name' => 'Definições', 'link' => '/comunicacao/definicoes'],
                ['key' => 'reclamacoes', 'name' => 'Reclamações', 'link' => '/comunicacao/reclamacoes'],
        ];
    } else {
        $menu = [
                ['key' => 'mensagens', 'name' => 'Mensagens', 'link' => '/comunicacao/mensagens'],
                ['key' => 'definicoes', 'name' => 'Definições', 'link' => '/comunicacao/definicoes'],
                ['key' => 'reclamacoes', 'name' => 'Reclamações', 'link' => '/comunicacao/reclamacoes'],
        ];
    }
    ?>
    @foreach($menu as $item)
        <div class="bloco {{$active==$item['key']?'sel':''}}">
            <a class="btn btn-menu brand-trans {{$active==$item['key']?'sel':''}}"
               href="{{$item['link']}}">{{$item['name']}}

            </a>
        </div>
    @endforeach
</div>