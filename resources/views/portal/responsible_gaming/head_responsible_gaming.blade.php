<?php
$menu = [

        ['key' => 'limites_apostas', 'name' => 'Limites', 'link' => '/jogo-responsavel/limites/apostas'],
        ['key' => 'auto-exclusao', 'name' => 'Auto-exclusão', 'link' => '/jogo-responsavel/autoexclusao'],
        ['key' => 'last_logins', 'name' => 'Últimos Acessos', 'link' => '/jogo-responsavel/last_logins'],
];
?>
<div class="box-links">
    @foreach($menu as $item)
        <div class="bloco {{$active==$item['key']?'sel':''}}">
            <a class="btn btn-menu brand-trans {{$active==$item['key']?'sel':''}}"
               href="{{$item['link']}}">{{$item['name']}}</a>
        </div>
    @endforeach
</div>