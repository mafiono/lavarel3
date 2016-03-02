<?php
$items = [
        ['game'=>'roleta_americana', 'name'=>'Roleta Americana'],
];
?>
<ul class="sub-menu">
    <li {{$game=='index'?'class=sel':''}}><a href="/info/regras/casino">Regras</a></li>
    @foreach($items as $item)
        <li {{$game==$item['game']?'class=sel':''}}><a href="/info/regras/casino/{{$item['game']}}">{{$item['name']}}</a></li>
    @endforeach
</ul>