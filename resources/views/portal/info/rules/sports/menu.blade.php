<?php
    $items = [
            ['game'=>'futebol', 'name'=>'Futebol'],
            ['game'=>'basquetebol', 'name'=>'Basquetebol'],
            ['game'=>'tenis', 'name'=>'Ténis'],
            ['game'=>'voleibol', 'name'=>'Voleibol'],
            ['game'=>'nhl', 'name'=>'NHL'],
            ['game'=>'andebol', 'name'=>'Andebol'],
            ['game'=>'boxe', 'name'=>'Boxe'],
            ['game'=>'criquete', 'name'=>'Críquete'],
            ['game'=>'dardos', 'name'=>'Dardos'],
            ['game'=>'desportos_motorizados', 'name'=>'Desportos Motorizados'],
            ['game'=>'futebol_americano', 'name'=>'Futebol Americano'],
            ['game'=>'outros_desportos', 'name'=>'Outros Desportos']
    ];
?>
<ul class="sub-menu">
    <li {{$game=='index'?'class=sel':''}}><a href="/info/regras/sports">SportsBook</a></li>
    @foreach($items as $item)
        <li {{$game==$item['game']?'class=sel':''}}><a href="/info/regras/sports/{{$item['game']}}">{{$item['name']}}</a></li>
    @endforeach
</ul>