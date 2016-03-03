<?php
$items = [
        ['game'=>'roleta_americana', 'name'=>'Roleta Americana'],
        ['game'=>'roleta_francesa', 'name'=>'Roleta Francesa'],
        ['game'=>'blackjack_21', 'name'=>'Blackjack/21'],
        ['game'=>'banca_francesa', 'name'=>'Banca Francesa'],
        ['game'=>'bacara_ponto', 'name'=>'Bacará Ponto e Banca'],
        ['game'=>'bingo', 'name'=>'Bingo'],
];
?>
<ul class="sub-menu">
    <li {{$game=='index'?'class=sel':''}}><a href="/info/regras/casino">Regras</a></li>
    @foreach($items as $item)
        <li {{$game==$item['game']?'class=sel':''}}><a href="/info/regras/casino/{{$item['game']}}">{{$item['name']}}</a></li>
    @endforeach
</ul>